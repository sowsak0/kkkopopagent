# OPOD Agent Architecture

## Overview

This project is a Laravel 12 web application whose main purpose is to aggregate hospital data from a HOSxP database and send summarized OPD/IPD/bed/hospital metrics to an external API.

The system has two main flows:

1. API data sender (`/api/opod-send`) to collect and transmit data.
2. Admin UI to manage lookup tables and settings.

## High-Level Architecture

```mermaid
flowchart TD
    subgraph Laravel App
        direction TB
        A[Routes]
        B[Api\nController]\n        C[Admin\nControllers]
        D[Database\nConfig]
        E[Models / Views]
    end

    subgraph Local DB
        M1[main_setting]
        M2[lookup_icd10]
        M3[lookup_hospcode]
        M4[lookup_ward]
    end

    subgraph HOSxP DB
        H1[ovst]
        H2[vn_stat]
        H3[ipt]
        H4[an_stat]
        H5[bedno]
        H6[iptadm]
        H7[refertables]
        H8[ward]
    end

    subgraph External API
        P1[/opd]
        P2[/ipd]
        P3[/ipd_bed_dep]
        P4[/hospital_config]
    end

    A -->|api.php| B
    A -->|web.php| C
    B --> D
    B --> HOSxP DB
    B --> External API
    C --> Local DB
    Local DB --> M1
    Local DB --> M2
    Local DB --> M3
    Local DB --> M4
```

## Core Components

### 1. `app/Http/Controllers/Api/OpodSendController.php`

- Main data integration controller.
- Handles `/api/opod-send` via `routes/api.php`.
- Reads configuration values from `main_setting`:
  - `opoh_token`
  - `opoh_url`
  - `bed_qty`
- Connects to the HOSxP database using the `hosxp` database connection.
- Builds and executes SQL queries for:
  - OPD summary
  - IPD summary
  - current hospital bed configuration
  - IPD bed usage by department
- Sends records in chunks to the remote API using Laravel HTTP client.

### 2. `routes/api.php`

- Defines API route:
  - `match(['get', 'post'], '/opod-send', [OpodSendController::class, 'send'])`
- Provides optional protected user route for `sanctum`.

### 3. `routes/web.php`

- Defines admin web routes for login and admin panel.
- Includes routes for lookup management and settings.

### 4. Admin controllers

#### `app/Http/Controllers/Admin/SettingController.php`
- Builds and upgrades supporting lookup tables.
- Manages `main_setting` values.

#### `app/Http/Controllers/Admin/LookupController.php`
- Supports CRUD for lookup records:
  - `lookup_icd10`
  - `lookup_hospcode`
  - `lookup_ward`
- Imports ward master data from HOSxP.
- Toggles Y/N flags used in the data queries.

### 5. `config/database.php`

- Defines the default Laravel database connection.
- Adds a secondary connection named `hosxp` for HOSxP data access.

## Data Flow

1. Request arrives at `/api/opod-send`.
2. `OpodSendController::send()` loads settings from `main_setting`.
3. The controller queries the HOSxP database for required metrics.
4. Query results are mapped into arrays of records.
5. Records are transmitted to external API endpoints in batches.
6. A summary response is returned.

## Database Dependencies

### Local Laravel DB
- `main_setting`: system configuration values.
- `lookup_icd10`: ICD-10 mappings used to classify OPD diagnoses.
- `lookup_hospcode`: hospital code mapping and province flags.
- `lookup_ward`: ward metadata and bed counts.

### HOSxP DB
- `opdconfig`: hospital code lookup.
- `ovst`: outpatient visits.
- `vn_stat`: visit financial and referral details.
- `ipt`: inpatient admission records.
- `an_stat`: inpatient discharge records.
- `bedno`: bed metadata.
- `iptadm`: current admission bed assignment.
- referral and service tables for various counts.

## Key Responsibility Assignment

- `OpodSendController` = data extraction and transport.
- `SettingController` = local configuration and database setup.
- `LookupController` = lookup master data management.
- `routes/api.php` = API endpoint exposure.
- `routes/web.php` = admin UI and management.

## Notes

- The `admdate` value in the IPD summary is currently computed as stay-days using `DATEDIFF(dchdate, admdate) + 1`.
- If the meaning of `Admit` is actually current inpatients or daily admissions, the IPD query logic should be adjusted accordingly.
- The app is designed to run in a Laravel environment with a live HOSxP MySQL/MariaDB database and an external API endpoint.
