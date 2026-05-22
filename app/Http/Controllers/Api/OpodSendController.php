<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class OpodSendController extends Controller
{
    public function send(Request $request)
    {
        set_time_limit(0);

        // 1) โหลดค่าพื้นฐาน-------------------------------------------------------------------------
        $token    = DB::table('main_setting')->where('name', 'opoh_token')->value('value');
        $baseUrl  = DB::table('main_setting')->where('name', 'opoh_url')->value('value');
        $hospcode = DB::connection('hosxp')->table('opdconfig')->value('hospitalcode');
        $bed_qty  = DB::table('main_setting')->where('name', 'bed_qty')->value('value');

        if (!$token || !$hospcode || !$baseUrl) {
            return response()->json([
                'ok' => false,
                'message' => 'Missing opoh_token, opoh_url in main_setting or hospitalcode in HOSxP opdconfig.'
            ], 422);
        }

        // 2) ช่วงวันที่ (default = 10 วันย้อนหลัง)---------------------------------------------------------------------
        $start = $request->input('start_date');
        $end   = $request->input('end_date');

        if (!$start || !$end) {
            $today = Carbon::today();
            $start = $today->copy()->subDays(10)->toDateString();
            $end   = $today->toDateString();
        }

        // 3) Query จากฐาน HOSxP (connection 'hosxp')
        // 3.1 ข้อมูล OPD--------------------------------------------------------------------------------------------
        $sqlOpd = '
            SELECT 
                ? AS hospcode, a.vstdate,
                COUNT(DISTINCT a.hn) AS hn_total,
                COUNT(a.vn) AS visit_total,
                SUM(CASE WHEN a.diagtype ="OP" THEN 1 ELSE 0 END) AS visit_total_op,
                SUM(CASE WHEN a.diagtype ="PP" THEN 1 ELSE 0 END) AS visit_total_pp,
                SUM(CASE WHEN a.hipdata_code IN ("UCS","WEL","DIS") AND a.paidst NOT IN ("01","03") AND a.incup = "Y" THEN 1 ELSE 0 END) AS visit_ucs_incup,
                SUM(CASE WHEN a.hipdata_code IN ("UCS","WEL","DIS") AND a.paidst NOT IN ("01","03") AND a.inprov = "Y" THEN 1 ELSE 0 END) AS visit_ucs_inprov,
                SUM(CASE WHEN a.hipdata_code IN ("UCS","WEL","DIS") AND a.paidst NOT IN ("01","03") AND a.outprov = "Y" THEN 1 ELSE 0 END) AS visit_ucs_outprov,
                SUM(CASE WHEN a.hipdata_code IN ("OFC") AND a.paidst NOT IN ("01","03") THEN 1 ELSE 0 END) AS visit_ofc,
                SUM(CASE WHEN a.hipdata_code IN ("BKK") AND a.paidst NOT IN ("01","03") THEN 1 ELSE 0 END) AS visit_bkk,
                SUM(CASE WHEN a.hipdata_code IN ("BMT") AND a.paidst NOT IN ("01","03") THEN 1 ELSE 0 END) AS visit_bmt,
                SUM(CASE WHEN a.hipdata_code IN ("SSS","SSI") AND a.paidst NOT IN ("01","03") THEN 1 ELSE 0 END) AS visit_sss,
                SUM(CASE WHEN a.hipdata_code IN ("LGO") AND a.paidst NOT IN ("01","03") THEN 1 ELSE 0 END) AS visit_lgo,
                SUM(CASE WHEN a.hipdata_code IN ("NRD","NRH") AND a.paidst NOT IN ("01","03") THEN 1 ELSE 0 END) AS visit_fss,
                SUM(CASE WHEN a.hipdata_code IN ("STP") AND a.paidst NOT IN ("01","03") THEN 1 ELSE 0 END) AS visit_stp,
                SUM(CASE WHEN (a.paidst IN ("01","03") OR a.hipdata_code IN ("A1","A9")) THEN 1 ELSE 0 END) AS visit_pay,
                COUNT(DISTINCT CASE WHEN a.healthmed = "Y" THEN a.vn END) AS visit_healthmed,
                COUNT(DISTINCT CASE WHEN a.dent = "Y" THEN a.vn END) AS visit_dent,
                COUNT(DISTINCT CASE WHEN a.physic = "Y" THEN a.vn END) AS visit_physic,
                COUNT(DISTINCT CASE WHEN a.anc = "Y" THEN a.vn END) AS visit_anc,
                COUNT(DISTINCT CASE WHEN a.telehealth = "Y" THEN a.vn END) AS visit_telehealth,
                COUNT(DISTINCT CASE WHEN a.referout_inprov = "Y" THEN a.vn END) AS visit_referout_inprov,
                COUNT(DISTINCT CASE WHEN a.referout_outprov = "Y" THEN a.vn END) AS visit_referout_outprov,
                COUNT(DISTINCT CASE WHEN a.referout_inprov_ipd = "Y" THEN a.vn END) AS visit_referout_inprov_ipd,
                COUNT(DISTINCT CASE WHEN a.referout_outprov_ipd = "Y" THEN a.vn END) AS visit_referout_outprov_ipd,
                COUNT(DISTINCT CASE WHEN a.referin_inprov = "Y" THEN a.vn END) AS visit_referin_inprov,
                COUNT(DISTINCT CASE WHEN a.referin_outprov = "Y" THEN a.vn END) AS visit_referin_outprov,
                COUNT(DISTINCT CASE WHEN a.referin_inprov_ipd = "Y" THEN a.vn END) AS visit_referin_inprov_ipd,
                COUNT(DISTINCT CASE WHEN a.referin_outprov_ipd = "Y" THEN a.vn END) AS visit_referin_outprov_ipd,
                MAX(COALESCE(rb.visit_referback_inprov, 0)) AS visit_referback_inprov,
                MAX(COALESCE(rb.visit_referback_outprov, 0)) AS visit_referback_outprov,
                MAX(COALESCE(op.visit_operation, 0)) AS visit_operation,
                SUM(a.income) AS inc_total, 
                SUM(a.inc03) AS inc_lab_total, 
                SUM(a.inc12) AS inc_drug_total,
                SUM(CASE WHEN a.hipdata_code IN ("UCS","WEL","DIS") AND a.paidst NOT IN ("01","03") AND a.incup = "Y" THEN a.income ELSE 0 END) AS inc_ucs_incup,
                SUM(CASE WHEN a.hipdata_code IN ("UCS","WEL","DIS") AND a.paidst NOT IN ("01","03") AND a.incup = "Y" THEN a.inc03 ELSE 0 END) AS inc_lab_ucs_incup,
                SUM(CASE WHEN a.hipdata_code IN ("UCS","WEL","DIS") AND a.paidst NOT IN ("01","03") AND a.incup = "Y" THEN a.inc12 ELSE 0 END) AS inc_drug_ucs_incup,
                SUM(CASE WHEN a.hipdata_code IN ("UCS","WEL","DIS") AND a.paidst NOT IN ("01","03") AND a.inprov = "Y" THEN a.income ELSE 0 END) AS inc_ucs_inprov,
                SUM(CASE WHEN a.hipdata_code IN ("UCS","WEL","DIS") AND a.paidst NOT IN ("01","03") AND a.inprov = "Y" THEN a.inc03 ELSE 0 END) AS inc_lab_ucs_inprov,
                SUM(CASE WHEN a.hipdata_code IN ("UCS","WEL","DIS") AND a.paidst NOT IN ("01","03") AND a.inprov = "Y" THEN a.inc12 ELSE 0 END) AS inc_drug_ucs_inprov,
                SUM(CASE WHEN a.hipdata_code IN ("UCS","WEL","DIS") AND a.paidst NOT IN ("01","03") AND a.outprov = "Y" THEN a.income ELSE 0 END) AS inc_ucs_outprov,
                SUM(CASE WHEN a.hipdata_code IN ("UCS","WEL","DIS") AND a.paidst NOT IN ("01","03") AND a.outprov = "Y" THEN a.inc03 ELSE 0 END) AS inc_lab_ucs_outprov,
                SUM(CASE WHEN a.hipdata_code IN ("UCS","WEL","DIS") AND a.paidst NOT IN ("01","03") AND a.outprov = "Y" THEN a.inc12 ELSE 0 END) AS inc_drug_ucs_outprov,
                SUM(CASE WHEN a.hipdata_code IN ("OFC") AND a.paidst NOT IN ("01","03") THEN a.income ELSE 0 END) AS inc_ofc,
                SUM(CASE WHEN a.hipdata_code IN ("OFC") AND a.paidst NOT IN ("01","03") THEN a.inc03 ELSE 0 END) AS inc_lab_ofc,
                SUM(CASE WHEN a.hipdata_code IN ("OFC") AND a.paidst NOT IN ("01","03") THEN a.inc12 ELSE 0 END) AS inc_drug_ofc,
                SUM(CASE WHEN a.hipdata_code IN ("BKK") AND a.paidst NOT IN ("01","03") THEN a.income ELSE 0 END) AS inc_bkk,
                SUM(CASE WHEN a.hipdata_code IN ("BKK") AND a.paidst NOT IN ("01","03") THEN a.inc03 ELSE 0 END) AS inc_lab_bkk,
                SUM(CASE WHEN a.hipdata_code IN ("BKK") AND a.paidst NOT IN ("01","03") THEN a.inc12 ELSE 0 END) AS inc_drug_bkk,
                SUM(CASE WHEN a.hipdata_code IN ("BMT") AND a.paidst NOT IN ("01","03") THEN a.income ELSE 0 END) AS inc_bmt,
                SUM(CASE WHEN a.hipdata_code IN ("BMT") AND a.paidst NOT IN ("01","03") THEN a.inc03 ELSE 0 END) AS inc_lab_bmt,
                SUM(CASE WHEN a.hipdata_code IN ("BMT") AND a.paidst NOT IN ("01","03") THEN a.inc12 ELSE 0 END) AS inc_drug_bmt,
                SUM(CASE WHEN a.hipdata_code IN ("SSS","SSI") AND a.paidst NOT IN ("01","03") THEN a.income ELSE 0 END) AS inc_sss,
                SUM(CASE WHEN a.hipdata_code IN ("SSS","SSI") AND a.paidst NOT IN ("01","03") THEN a.inc03 ELSE 0 END) AS inc_lab_sss,
                SUM(CASE WHEN a.hipdata_code IN ("SSS","SSI") AND a.paidst NOT IN ("01","03") THEN a.inc12 ELSE 0 END) AS inc_drug_sss,
                SUM(CASE WHEN a.hipdata_code IN ("LGO") AND a.paidst NOT IN ("01","03") THEN a.income ELSE 0 END) AS inc_lgo,
                SUM(CASE WHEN a.hipdata_code IN ("LGO") AND a.paidst NOT IN ("01","03") THEN a.inc03 ELSE 0 END) AS inc_lab_lgo,
                SUM(CASE WHEN a.hipdata_code IN ("LGO") AND a.paidst NOT IN ("01","03") THEN a.inc12 ELSE 0 END) AS inc_drug_lgo,
                SUM(CASE WHEN a.hipdata_code IN ("NRD","NRH") AND a.paidst NOT IN ("01","03") THEN a.income ELSE 0 END) AS inc_fss,
                SUM(CASE WHEN a.hipdata_code IN ("NRD","NRH") AND a.paidst NOT IN ("01","03") THEN a.inc03 ELSE 0 END) AS inc_lab_fss,
                SUM(CASE WHEN a.hipdata_code IN ("NRD","NRH") AND a.paidst NOT IN ("01","03") THEN a.inc12 ELSE 0 END) AS inc_drug_fss,
                SUM(CASE WHEN a.hipdata_code IN ("STP") AND a.paidst NOT IN ("01","03") THEN a.income ELSE 0 END) AS inc_stp,
                SUM(CASE WHEN a.hipdata_code IN ("STP") AND a.paidst NOT IN ("01","03") THEN a.inc03 ELSE 0 END) AS inc_lab_stp,
                SUM(CASE WHEN a.hipdata_code IN ("STP") AND a.paidst NOT IN ("01","03") THEN a.inc12 ELSE 0 END) AS inc_drug_stp,
                SUM(CASE WHEN (a.hipdata_code IN ("A1","A9") OR a.paidst IN ("01","03")) THEN a.income ELSE 0 END) AS inc_pay,
                SUM(CASE WHEN (a.hipdata_code IN ("A1","A9") OR a.paidst IN ("01","03")) THEN a.inc03 ELSE 0 END) AS inc_lab_pay,
                SUM(CASE WHEN (a.hipdata_code IN ("A1","A9") OR a.paidst IN ("01","03")) THEN a.inc12 ELSE 0 END) AS inc_drug_pay
            FROM (
                SELECT ov.vstdate, ov.vn, ov.hn, v.cid, v.income, v.inc03, v.inc12, p.hipdata_code, p.paidst,
                    IF(i.icd10 IS NULL, "OP", "PP") AS diagtype,
                    IF(vp.hospmain IS NOT NULL, "Y", "") AS incup,
                    IF(vp1.hospmain IS NOT NULL, "Y", "") AS inprov,
                    IF(vp2.hospmain IS NOT NULL, "Y", "") AS outprov,
                    IF(dt.vn IS NOT NULL, "Y", "") AS dent,
                    IF(pl.vn IS NOT NULL, "Y", "") AS physic,
                    IF(hm.vn IS NOT NULL, "Y", "") AS healthmed,
                    IF(anc.vn IS NOT NULL, "Y", "") AS anc,
                    IF(oi.export_code = 5, "Y", "") AS telehealth,
                    IF(r.vn IS NOT NULL, "Y", "") AS referout_inprov, IF(r1.vn IS NOT NULL, "Y", "") AS referout_outprov,
                    IF(re.vn IS NOT NULL, "Y", "") AS referout_inprov_ipd, IF(re1.vn IS NOT NULL, "Y", "") AS referout_outprov_ipd,
                    IF(ri.vn IS NOT NULL AND ip.vn IS NULL, "Y", "") AS referin_inprov, IF(ri1.vn IS NOT NULL AND ip.vn IS NULL, "Y", "") AS referin_outprov,
                    IF(rii.vn IS NOT NULL AND ip.vn IS NOT NULL, "Y", "") AS referin_inprov_ipd, IF(rii1.vn IS NOT NULL AND ip.vn IS NOT NULL, "Y", "") AS referin_outprov_ipd
                FROM ovst ov
                LEFT JOIN vn_stat v ON v.vn = ov.vn
                LEFT JOIN ipt ip ON ip.vn = ov.vn
                LEFT JOIN pttype p ON p.pttype = ov.pttype
                LEFT JOIN ovstist oi ON oi.ovstist = ov.ovstist
                LEFT JOIN opod_agent.lookup_icd10 i ON i.icd10 = v.pdx AND i.pp = "Y"
                LEFT JOIN visit_pttype vp ON vp.vn = ov.vn AND vp.hospmain IN (SELECT hospcode FROM opod_agent.lookup_hospcode WHERE hmain_ucs = "Y")
                LEFT JOIN visit_pttype vp1 ON vp1.vn = ov.vn AND vp1.hospmain IN (SELECT hospcode FROM opod_agent.lookup_hospcode WHERE in_province = "Y" AND (hmain_ucs = "" OR hmain_ucs IS NULL))
                LEFT JOIN visit_pttype vp2 ON vp2.vn = ov.vn AND vp2.hospmain NOT IN (SELECT hospcode FROM opod_agent.lookup_hospcode WHERE in_province = "Y")
                LEFT JOIN (SELECT DISTINCT vn FROM dtmain) dt ON dt.vn = ov.vn
                LEFT JOIN (SELECT DISTINCT vn FROM physic_list) pl ON pl.vn = ov.vn
                LEFT JOIN (SELECT DISTINCT vn FROM health_med_service) hm ON hm.vn = ov.vn
                LEFT JOIN (SELECT DISTINCT vn FROM person_anc_service) anc ON anc.vn = ov.vn
                LEFT JOIN (SELECT vn FROM referout WHERE refer_hospcode IN (SELECT hospcode FROM opod_agent.lookup_hospcode WHERE in_province = "Y")) r ON r.vn = ov.vn
                LEFT JOIN (SELECT vn FROM referout WHERE refer_hospcode NOT IN (SELECT hospcode FROM opod_agent.lookup_hospcode WHERE in_province = "Y")) r1 ON r1.vn = ov.vn
                LEFT JOIN (SELECT vn FROM referout WHERE refer_hospcode IN (SELECT hospcode FROM opod_agent.lookup_hospcode WHERE in_province = "Y") AND department = "IPD") re ON re.vn = ov.vn
                LEFT JOIN (SELECT vn FROM referout WHERE refer_hospcode NOT IN (SELECT hospcode FROM opod_agent.lookup_hospcode WHERE in_province = "Y") AND department = "IPD") re1 ON re1.vn = ov.vn
                LEFT JOIN (SELECT vn FROM referin WHERE hospcode IN (SELECT hospcode FROM opod_agent.lookup_hospcode WHERE in_province = "Y")) ri ON ri.vn = ov.vn
                LEFT JOIN (SELECT vn FROM referin WHERE hospcode NOT IN (SELECT hospcode FROM opod_agent.lookup_hospcode WHERE in_province = "Y")) ri1 ON ri1.vn = ov.vn
                LEFT JOIN (SELECT vn FROM referin WHERE hospcode IN (SELECT hospcode FROM opod_agent.lookup_hospcode WHERE in_province = "Y")) rii ON rii.vn = ov.vn
                LEFT JOIN (SELECT vn FROM referin WHERE hospcode NOT IN (SELECT hospcode FROM opod_agent.lookup_hospcode WHERE in_province = "Y")) rii1 ON rii1.vn = ov.vn
                WHERE ov.vstdate BETWEEN ? AND ?
            ) a
            LEFT JOIN (
                SELECT DATE(reply_date_time) as d,
                COUNT(DISTINCT CASE WHEN lh.in_province = "Y" THEN vn END) as visit_referback_inprov,
                COUNT(DISTINCT CASE WHEN lh.in_province != "Y" OR lh.in_province IS NULL THEN vn END) as visit_referback_outprov
                FROM refer_reply rr
                LEFT JOIN opod_agent.lookup_hospcode lh ON rr.dest_hospcode = lh.hospcode
                WHERE reply_date_time BETWEEN CONCAT(?, " 00:00:00") AND CONCAT(?, " 23:59:59")
                GROUP BY 1
            ) rb ON a.vstdate = rb.d
            LEFT JOIN (
                SELECT request_date as d, COUNT(DISTINCT operation_id) as visit_operation FROM operation_list 
                WHERE request_date BETWEEN ? AND ? GROUP BY 1
            ) op ON a.vstdate = op.d
            GROUP BY a.vstdate
            ORDER BY a.vstdate';

        $rowsOpd = DB::connection('hosxp')->select(
            $sqlOpd,
            [
                $hospcode,
                $start,
                $end,
                $start,
                $end,
                $start,
                $end
            ]
        );

        $opdRecords = array_map(function ($r) {
            return [
                'vstdate'                       => $r->vstdate,
                'hn_total'                      => (int)$r->hn_total,
                'visit_total'                   => (int)$r->visit_total,
                'visit_total_op'                => (int)$r->visit_total_op,
                'visit_total_pp'                => (int)$r->visit_total_pp,
                'visit_ucs_incup'               => (int)$r->visit_ucs_incup,
                'visit_ucs_inprov'              => (int)$r->visit_ucs_inprov,
                'visit_ucs_outprov'             => (int)$r->visit_ucs_outprov,
                'visit_ofc'                     => (int)$r->visit_ofc,
                'visit_bkk'                     => (int)$r->visit_bkk,
                'visit_bmt'                     => (int)$r->visit_bmt,
                'visit_sss'                     => (int)$r->visit_sss,
                'visit_lgo'                     => (int)$r->visit_lgo,
                'visit_fss'                     => (int)$r->visit_fss,
                'visit_stp'                     => (int)$r->visit_stp,
                'visit_pay'                     => (int)$r->visit_pay,
                'visit_healthmed'               => (int)$r->visit_healthmed,
                'visit_dent'                    => (int)$r->visit_dent,
                'visit_physic'                  => (int)$r->visit_physic,
                'visit_anc'                     => (int)$r->visit_anc,
                'visit_telehealth'              => (int)$r->visit_telehealth,
                'visit_operation'               => (int)$r->visit_operation,
                'visit_referout_inprov'         => (int)$r->visit_referout_inprov,
                'visit_referout_outprov'        => (int)$r->visit_referout_outprov,
                'visit_referout_inprov_ipd'     => (int)$r->visit_referout_inprov_ipd,
                'visit_referout_outprov_ipd'    => (int)$r->visit_referout_outprov_ipd,
                'visit_referin_inprov'          => (int)$r->visit_referin_inprov,
                'visit_referin_outprov'         => (int)$r->visit_referin_outprov,
                'visit_referin_inprov_ipd'      => (int)$r->visit_referin_inprov_ipd,
                'visit_referin_outprov_ipd'     => (int)$r->visit_referin_outprov_ipd,
                'visit_referback_inprov'        => (int)$r->visit_referback_inprov,
                'visit_referback_outprov'       => (int)$r->visit_referback_outprov,
                'inc_total'                     => (float)$r->inc_total,
                'inc_lab_total'                 => (float)$r->inc_lab_total,
                'inc_drug_total'                => (float)$r->inc_drug_total,
                'inc_ucs_incup'                 => (float)$r->inc_ucs_incup,
                'inc_lab_ucs_incup'             => (float)$r->inc_lab_ucs_incup,
                'inc_drug_ucs_incup'            => (float)$r->inc_drug_ucs_incup,
                'inc_ucs_inprov'                => (float)$r->inc_ucs_inprov,
                'inc_lab_ucs_inprov'            => (float)$r->inc_lab_ucs_inprov,
                'inc_drug_ucs_inprov'           => (float)$r->inc_drug_ucs_inprov,
                'inc_ucs_outprov'               => (float)$r->inc_ucs_outprov,
                'inc_lab_ucs_outprov'           => (float)$r->inc_lab_ucs_outprov,
                'inc_drug_ucs_outprov'          => (float)$r->inc_drug_ucs_outprov,
                'inc_ofc'                       => (float)$r->inc_ofc,
                'inc_lab_ofc'                   => (float)$r->inc_lab_ofc,
                'inc_drug_ofc'                  => (float)$r->inc_drug_ofc,
                'inc_bkk'                       => (float)$r->inc_bkk,
                'inc_lab_bkk'                   => (float)$r->inc_lab_bkk,
                'inc_drug_bkk'                  => (float)$r->inc_drug_bkk,
                'inc_bmt'                       => (float)$r->inc_bmt,
                'inc_lab_bmt'                   => (float)$r->inc_lab_bmt,
                'inc_drug_bmt'                  => (float)$r->inc_drug_bmt,
                'inc_sss'                       => (float)$r->inc_sss,
                'inc_lab_sss'                   => (float)$r->inc_lab_sss,
                'inc_drug_sss'                  => (float)$r->inc_drug_sss,
                'inc_lgo'                       => (float)$r->inc_lgo,
                'inc_lab_lgo'                   => (float)$r->inc_lab_lgo,
                'inc_drug_lgo'                  => (float)$r->inc_drug_lgo,
                'inc_fss'                       => (float)$r->inc_fss,
                'inc_lab_fss'                   => (float)$r->inc_lab_fss,
                'inc_drug_fss'                  => (float)$r->inc_drug_fss,
                'inc_stp'                       => (float)$r->inc_stp,
                'inc_lab_stp'                   => (float)$r->inc_lab_stp,
                'inc_drug_stp'                  => (float)$r->inc_drug_stp,
                'inc_pay'                       => (float)$r->inc_pay,
                'inc_lab_pay'                   => (float)$r->inc_lab_pay,
                'inc_drug_pay'                  => (float)$r->inc_drug_pay,
            ];
        }, $rowsOpd);

        // 3.2 ข้อมูล IPD-----------------------------------------------------------------------------------------------------------
        $sqlIpd = '
            SELECT ? AS hospcode,dchdate,COUNT(DISTINCT an) AS an_total ,sum(admdate) AS admdate,        
            ROUND((SUM(a.admdate) * 100) / (? * CASE WHEN YEAR(a.dchdate) = YEAR(CURDATE()) AND MONTH(a.dchdate) = MONTH(CURDATE()) 
                THEN DAY(CURDATE()) ELSE DAY(LAST_DAY(a.dchdate))END), 2) AS bed_occupancy,
            ROUND((SUM(a.admdate) / CASE WHEN YEAR(a.dchdate) = YEAR(CURDATE()) AND MONTH(a.dchdate) = MONTH(CURDATE()) 
                THEN DAY(CURDATE()) ELSE DAY(LAST_DAY(a.dchdate)) END), 2) AS active_bed, 
			ROUND(SUM(adjrw)/COUNT(DISTINCT an),2) AS cmi,ROUND(SUM(adjrw),5) AS adjrw, 
            SUM(income) AS inc_total,
			SUM(inc03) AS inc_lab_total,
            SUM(inc12) AS inc_drug_total
			FROM (SELECT a.dchdate,a.an,a.admdate,i.adjrw,a.income,a.inc03,inc12
			FROM ipt i
			LEFT JOIN an_stat a ON a.an=i.an
			LEFT JOIN pttype p ON p.pttype=a.pttype
            WHERE a.dchdate BETWEEN ? AND ?
            AND a.pdx NOT IN ("Z290","Z208")
            GROUP BY a.an, a.dchdate, a.admdate, i.adjrw, a.income, a.inc03, inc12 ) AS a
			GROUP BY dchdate';

        $rowsIpd = DB::connection('hosxp')->select($sqlIpd, [$hospcode, $bed_qty, $start, $end]);

        $ipdRecords = array_map(function ($r) {
            return [
                'dchdate'           => $r->dchdate,
                'an_total'          => (int)$r->an_total,
                'admdate'           => (int)$r->admdate,
                'bed_occupancy'     => (float)$r->bed_occupancy,
                'active_bed'        => (float)$r->active_bed,
                'cmi'               => (float)$r->cmi,
                'adjrw'             => (float)$r->adjrw,
                'inc_total'         => (float)$r->inc_total,
                'inc_lab_total'     => (float)$r->inc_lab_total,
                'inc_drug_total'    => (float)$r->inc_drug_total,
            ];
        }, $rowsIpd);

        // 3.3 ข้อมูล UPdate Hospital ปัจจุบัน-------------------------------------------------------------------------------------------------------
        $sqlhospital = '
            SELECT ? AS hospcode,IFNULL((SELECT SUM(bed_qty) FROM opod_agent.lookup_ward 
            WHERE (ward_normal = "Y" OR ward_m ="Y" OR ward_f ="Y" OR ward_vip="Y")),0) AS bed_qty,
            IFNULL(COUNT(DISTINCT bedno),0) AS bed_use
            FROM (SELECT i.an,i.regdate,i.regtime,i.ward,b.bedno,b.export_code
            FROM ipt i 
			INNER JOIN iptadm ia ON ia.an = i.an
            LEFT JOIN bedno b ON b.bedno=ia.bedno
			WHERE i.confirm_discharge = "N" 
			AND b.export_code IS NOT NULL AND b.export_code <>"") AS a ';

        $rowshospital = DB::connection('hosxp')->select($sqlhospital, [$hospcode]);

        $hospitalRecords = array_map(function ($r) use ($hospcode, $bed_qty) {
            return [
                'hospcode' => $hospcode,
                'bed_qty'  => (int)($r->bed_qty ?? $bed_qty ?? 0),
                'bed_use'  => (int)($r->bed_use ?? 0),
            ];
        }, $rowshospital);

        // 3.4 ข้อมูล IPD_bed-----------------------------------------------------------------------------------------------------------
        $sqlIpd_bed = '
            SELECT ? AS hospcode,
            IFNULL(b.export_code,0) AS bed_code,
            IFNULL(COUNT(DISTINCT b.bedno),0) AS bed_qty,
            IFNULL(MAX(b1.bed_use),0) AS bed_use
            FROM bedno b
            LEFT JOIN (SELECT b.export_code,COUNT(DISTINCT b.bedno) AS bed_use
            FROM ipt i
            INNER JOIN iptadm ia ON ia.an=i.an
            LEFT JOIN bedno b ON b.bedno=ia.bedno
            WHERE b.export_code IS NOT NULL AND b.export_code <>""
            AND i.confirm_discharge = "N"
            GROUP BY b.export_code) b1 ON b1.export_code=b.export_code
            WHERE b.export_code IS NOT NULL AND b.export_code <>""
            GROUP BY b.export_code 
            ORDER BY b.export_code';

        $rowsIpd_bed = DB::connection('hosxp')->select($sqlIpd_bed, [$hospcode]);

        $ipdbedRecords = array_map(function ($r) {
            return [
                'bed_code' => (string)$r->bed_code,
                'bed_qty'  => (int)$r->bed_qty,
                'bed_use'  => (int)$r->bed_use,
            ];
        }, $rowsIpd_bed);

        // 4) ส่งข้อมูลไปยัง API ปลายทาง-----------------------------------------------------------------------------------------------

        $chunkSize = (int)($request->query('chunk', 200));

        // $baseUrl ดึงมาจากด้านบนแล้ว

        // ---- OPD ----
        $summaryOpd = $this->sendChunks($opdRecords, "$baseUrl/opd", $token, $hospcode, 'OPD', $chunkSize);

        // ---- IPD ----
        $summaryIpd = $this->sendChunks($ipdRecords, "$baseUrl/ipd", $token, $hospcode, 'IPD', $chunkSize);

        // ---- IPD BED ----
        $summaryIpd_bed = $this->sendChunks($ipdbedRecords, "$baseUrl/ipd_bed_dep", $token, $hospcode, 'IPD_BED', $chunkSize);

        // ---- HOSPITAL ----
        $summaryHospital = $this->sendChunks($hospitalRecords, "$baseUrl/hospital_config", $token, $hospcode, 'HOSPITAL', $chunkSize);

        // 5) สรุปผลรวม
        return response()->json([
            'ok'         => $summaryOpd['failed'] === 0
                && $summaryIpd['failed'] === 0
                && $summaryIpd_bed['failed'] === 0
                && $summaryHospital['failed'] === 0,
            'hospcode'   => $hospcode,
            'start_date' => $start,
            'end_date'   => $end,
            'received'   => [
                'opd' => count($opdRecords),
                'ipd' => count($ipdRecords),
                'ipd_bed' => count($ipdbedRecords),
                'hospital' => count($hospitalRecords),
            ],
            'sent_summary' => [
                'opd' => $summaryOpd,
                'ipd' => $summaryIpd,
                'ipd_bed' => $summaryIpd_bed,
                'hospital' => $summaryHospital,
            ]
        ], 200);
    }

    private function sendChunks(array $records, string $url, string $token, string $hospcode, string $prefix, int $chunkSize)
    {
        $chunks = array_chunk($records, max(1, $chunkSize));
        $summary = [
            'batches' => count($chunks),
            'sent'    => 0,
            'failed'  => 0,
            'details' => [],
        ];

        foreach ($chunks as $i => $chunk) {
            $dates = match ($prefix) {
                'OPD' => array_column($chunk, 'vstdate'),
                'IPD' => array_column($chunk, 'dchdate'),
                default => [] 
            };
            sort($dates);
            $idempotencyKey = hash('sha256', $hospcode . "|$prefix|" . implode(',', $dates));

            try {
                $res = Http::withToken($token)
                    ->acceptJson()
                    ->timeout(20)
                    ->retry(3, 300)
                    ->withHeaders([
                        'Idempotency-Key' => $idempotencyKey,
                    ])
                    ->post($url, ['records' => $chunk]);

                $status = $res->status();
                $ok = $res->successful() || $status === 207;

                $summary[$ok ? 'sent' : 'failed'] += count($chunk);
                $summary['details'][] = [
                    'batch'  => $i + 1,
                    'size'   => count($chunk),
                    'status' => $status,
                    'body'   => $res->json() ?? $res->body(),
                ];
            } catch (\Throwable $e) {
                $summary['failed'] += count($chunk);
                $summary['details'][] = [
                    'batch'  => $i + 1,
                    'size'   => count($chunk),
                    'status' => 'exception',
                    'error'  => $e->getMessage(),
                ];
            }
        }

        return $summary;
    }
}
