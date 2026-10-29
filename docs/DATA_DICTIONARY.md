# EcoHorizon Global — Data Dictionary

This document provides a comprehensive, field-by-field specification of the database architecture powering the **EcoHorizon Global — Global Multimodal Freight Visibility Platform**.

---

## 1. Schema Overview

The database design adheres to 3NF (Third Normal Form) principles with strict referential integrity, indexing for high-frequency queries (such as public tracking numbers, user roles, shipment status, and event timestamps), and comprehensive audit trails.

| Table Name | Entity Description | Primary Key | Total Columns |
| :--- | :--- | :--- | :--- |
| **`users`** | System actors across 3 distinct roles (Admin, Operator, Customer). | `id` (BIGINT UNSIGNED) | 10 |
| **`personal_access_tokens`** | Laravel Sanctum API authentication tokens. | `id` (BIGINT UNSIGNED) | 8 |
| **`service_levels`** | Contractual delivery arrangements (`DOOR_TO_DOOR`, `DOOR_TO_PORT`, `PORT_TO_PORT`, `B2B`). | `id` (BIGINT UNSIGNED) | 8 |
| **`locations`** | Global logistics nodes (Seaports, Airports, Warehouses, Terminals, Addresses). | `id` (BIGINT UNSIGNED) | 13 |
| **`carbon_emission_factors`** | GLEC Framework v3.0 emission baselines per mode and vessel/vehicle type. | `id` (BIGINT UNSIGNED) | 9 |
| **`shipments`** | Master freight consignment records, status lifecycle, aggregated metrics. | `id` (BIGINT UNSIGNED) | 26 |
| **`transport_legs`** | Sequential journey segments (Sea, Land, Air) with carrier and leg emissions. | `id` (BIGINT UNSIGNED) | 17 |
| **`tracking_events`** | Immutable milestone timeline entries with coordinates and proof data. | `id` (BIGINT UNSIGNED) | 13 |
| **`delivery_exceptions`** | Operational disruptions, weather delays, and customs hold records with ETA impact. | `id` (BIGINT UNSIGNED) | 13 |
| **`audit_logs`** | Complete regulatory and compliance audit trail tracking model state changes. | `id` (BIGINT UNSIGNED) | 11 |

---

## 2. Table Specifications

### 2.1. `users`
Stores user identities, credentials, role-based authorization designations, and corporate affiliations.

| Column Name | Data Type | Nullable | Default | Constraints & Indexes | Business Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto-increment | Primary Key | Unique user identifier. |
| `name` | `VARCHAR(255)` | No | None | None | Full legal or operational name of user. |
| `email` | `VARCHAR(255)` | No | None | Unique Index | Authentication email address. |
| `email_verified_at` | `TIMESTAMP` | Yes | `NULL` | None | Timestamp of email verification. |
| `password` | `VARCHAR(255)` | No | None | None | Bcrypt-hashed password. |
| `role` | `VARCHAR(255)` | No | `'customer'` | Index (`admin`, `operator`, `customer`) | Server-enforced role for RBAC authorization. |
| `company_name` | `VARCHAR(255)` | Yes | `NULL` | None | Corporate organization (e.g. shipper, carrier, consignee). |
| `phone` | `VARCHAR(255)` | Yes | `NULL` | None | Contact telephone or mobile number. |
| `status` | `VARCHAR(255)` | No | `'active'` | Index (`active`, `suspended`) | Account lifecycle status. |
| `remember_token` | `VARCHAR(100)` | Yes | `NULL` | None | Laravel "remember me" authentication token. |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | None | Record creation timestamp. |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | None | Record last modification timestamp. |

---

### 2.2. `personal_access_tokens`
Laravel Sanctum access tokens for secure REST API communication.

| Column Name | Data Type | Nullable | Default | Constraints & Indexes | Business Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto-increment | Primary Key | Unique token identifier. |
| `tokenable_type` | `VARCHAR(255)` | No | None | Composite Index (`tokenable_type`, `tokenable_id`) | Polymorphic owner model (e.g. `App\Models\User`). |
| `tokenable_id` | `BIGINT UNSIGNED` | No | None | Composite Index | Foreign key of token owner. |
| `name` | `VARCHAR(255)` | No | None | None | Label for the token (e.g. `"Mobile App"`, `"API Key"`). |
| `token` | `VARCHAR(64)` | No | None | Unique Index | SHA-256 hashed bearer token. |
| `abilities` | `TEXT` | Yes | `NULL` | None | JSON array of token permissions. |
| `last_used_at` | `TIMESTAMP` | Yes | `NULL` | None | Timestamp when token was last presented. |
| `expires_at` | `TIMESTAMP` | Yes | `NULL` | None | Optional expiration timestamp. |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | None | Token generation timestamp. |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | None | Token update timestamp. |

---

### 2.3. `service_levels`
Defines contractual service arrangements that govern the shipment's scope of responsibility and delivery boundary conditions.

| Column Name | Data Type | Nullable | Default | Constraints & Indexes | Business Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto-increment | Primary Key | Unique service level ID. |
| `code` | `VARCHAR(255)` | No | None | Unique Index (`DOOR_TO_DOOR`, `DOOR_TO_PORT`, `PORT_TO_PORT`, `B2B`) | Business code determining lifecycle and completion logic. |
| `name` | `VARCHAR(255)` | No | None | None | Human-readable title (e.g. "Door-to-Door Delivery"). |
| `description` | `TEXT` | No | None | None | Operational explanation of scope and handover rules. |
| `icon` | `VARCHAR(255)` | Yes | `NULL` | None | Heroicon or SVG identifier for UI rendering. |
| `requires_pickup` | `BOOLEAN` | No | `true` | None | Indicates if provider collects cargo at origin address. |
| `requires_recipient_delivery` | `BOOLEAN` | No | `true` | None | If `false`, journey concludes at port/terminal or customer pickup. |
| `is_active` | `BOOLEAN` | No | `true` | None | Availability flag for new shipment creation. |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | None | Record creation timestamp. |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | None | Record update timestamp. |

---

### 2.4. `locations`
Geographic master data representing multimodal logistics nodes with precise coordinates for distance calculations and map plotting.

| Column Name | Data Type | Nullable | Default | Constraints & Indexes | Business Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto-increment | Primary Key | Unique location ID. |
| `name` | `VARCHAR(255)` | No | None | None | Facility name (e.g. "Port of Tanjung Priok", "Munich Distribution Hub"). |
| `code` | `VARCHAR(255)` | Yes | `NULL` | Index | Official UN/LOCODE or IATA code (e.g. `IDTPP`, `SGSIN`, `USLAX`, `DEHAM`). |
| `type` | `VARCHAR(255)` | No | None | None | Node type: `seaport`, `airport`, `warehouse`, `terminal`, `door_address`. |
| `address` | `VARCHAR(255)` | No | None | None | Street address or berth/dock designation. |
| `city` | `VARCHAR(255)` | No | None | None | City name. |
| `state` | `VARCHAR(255)` | Yes | `NULL` | None | Province or state. |
| `country` | `VARCHAR(255)` | No | None | None | Full country name. |
| `country_code` | `VARCHAR(3)` | No | None | Index | ISO 3166-1 alpha-2 or alpha-3 code (e.g. `ID`, `SG`, `DE`, `US`). |
| `latitude` | `DECIMAL(10, 7)` | No | None | None | WGS84 latitude coordinate for Leaflet and Haversine math. |
| `longitude` | `DECIMAL(10, 7)` | No | None | None | WGS84 longitude coordinate for Leaflet and Haversine math. |
| `contact_person` | `VARCHAR(255)` | Yes | `NULL` | None | Local dispatcher or terminal manager name. |
| `contact_phone` | `VARCHAR(255)` | Yes | `NULL` | None | Direct dispatch hotline. |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | None | Record creation timestamp. |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | None | Record update timestamp. |

---

### 2.5. `carbon_emission_factors`
GLEC Framework v3.0 emission intensity factors utilized for greenhouse gas (GHG) calculations.

| Column Name | Data Type | Nullable | Default | Constraints & Indexes | Business Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto-increment | Primary Key | Unique factor identifier. |
| `transport_mode` | `VARCHAR(255)` | No | None | Index (`sea`, `land`, `air`) | Transportation mode classification. |
| `subcategory` | `VARCHAR(255)` | No | None | None | Vehicle/vessel profile (e.g. "Container Vessel (8000+ TEU)", "Long-haul Diesel Truck"). |
| `factor_g_per_tonne_km` | `DECIMAL(10, 4)` | No | None | None | Emission intensity in grams $\text{CO}_2\text{e}$ per tonne-kilometer ($\text{g CO}_2\text{e} / \text{t-km}$). |
| `source_reference` | `VARCHAR(255)` | No | None | None | Scientific accreditation (e.g. GLEC Framework v3.0, IMO MEPC.308(73)). |
| `description` | `TEXT` | Yes | `NULL` | None | Technical assumptions and fuel basis description. |
| `effective_year` | `INT` | No | `2024` | None | Benchmark year for standard factor compliance. |
| `is_default` | `BOOLEAN` | No | `false` | None | Flag indicating default selection for the transport mode. |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | None | Record creation timestamp. |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | None | Record update timestamp. |

---

### 2.6. `shipments`
The core entity capturing end-to-end multimodal consignments, cargo specs, parties, aggregated distance & carbon, and lifecycle progression.

| Column Name | Data Type | Nullable | Default | Constraints & Indexes | Business Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto-increment | Primary Key | Internal surrogate key. |
| `tracking_number` | `VARCHAR(255)` | No | None | Unique Index (`EHG-YYYYMMDD-XXXX`) | Public tracking code shared with customers. |
| `customer_user_id` | `BIGINT UNSIGNED` | Yes | `NULL` | Foreign Key -> `users(id)` ON DELETE SET NULL | Customer account authorized to view shipment. |
| `assigned_operator_id` | `BIGINT UNSIGNED` | Yes | `NULL` | Foreign Key -> `users(id)` ON DELETE SET NULL | Operations coordinator responsible for shipment. |
| `service_level_id` | `BIGINT UNSIGNED` | No | None | Foreign Key -> `service_levels(id)` | Contractual delivery arrangement. |
| `destination_type` | `VARCHAR(255)` | No | None | None | Delivery destination type: `door_address`, `seaport_terminal`, `airport_terminal`, `business_warehouse`, `fulfillment_center`. |
| `origin_location_id` | `BIGINT UNSIGNED` | No | None | Foreign Key -> `locations(id)` | Initial pickup or departure origin node. |
| `destination_location_id` | `BIGINT UNSIGNED` | No | None | Foreign Key -> `locations(id)` | Ultimate contractual destination node. |
| `sender_business_name` | `VARCHAR(255)` | No | None | None | Shipper legal company name. |
| `sender_contact_name` | `VARCHAR(255)` | No | None | None | Shipper dispatch contact person. |
| `sender_phone` | `VARCHAR(255)` | No | None | None | Shipper contact telephone. |
| `sender_email` | `VARCHAR(255)` | Yes | `NULL` | None | Shipper notification email. |
| `sender_address` | `TEXT` | No | None | None | Shipper physical address. |
| `receiver_business_name` | `VARCHAR(255)` | No | None | None | Consignee legal company name. |
| `receiver_contact_name` | `VARCHAR(255)` | No | None | None | Consignee designated receiving officer. |
| `receiver_phone` | `VARCHAR(255)` | No | None | None | Consignee contact telephone. |
| `receiver_email` | `VARCHAR(255)` | Yes | `NULL` | None | Consignee notification email. |
| `receiver_address` | `TEXT` | No | None | None | Delivery destination address. |
| `cargo_description` | `VARCHAR(255)` | No | None | None | Cargo description (e.g. "Precision Electronics", "Automotive Parts"). |
| `cargo_weight_tonnes` | `DECIMAL(10, 3)` | No | None | None | Cargo net/gross weight in metric tonnes. |
| `cargo_volume_cbm` | `DECIMAL(10, 3)` | Yes | `NULL` | None | Cargo volume in cubic meters ($\text{m}^3$). |
| `package_count` | `INT` | No | `1` | None | Quantity of packages/pallets/containers. |
| `package_type` | `VARCHAR(255)` | No | `'Standard Pallets'` | None | Packaging format (e.g. 40ft Container, Euro Pallets, Wooden Crates). |
| `special_instructions` | `TEXT` | Yes | `NULL` | None | Handling precautions (e.g. temperature controlled, fragile, hazardous). |
| `current_status` | `VARCHAR(255)` | No | `'CREATED'` | Index | Validated lifecycle status. |
| `eta` | `DATETIME` | Yes | `NULL` | None | Current estimated date and time of arrival. |
| `original_eta` | `DATETIME` | Yes | `NULL` | None | Baseline scheduled arrival prior to operational delays. |
| `actual_delivery_date` | `DATETIME` | Yes | `NULL` | None | Timestamp of verified final delivery/handover. |
| `total_distance_km` | `DECIMAL(10, 2)` | No | `0.00` | None | Aggregated journey distance across all transport legs. |
| `total_estimated_co2e_kg` | `DECIMAL(10, 2)` | No | `0.00` | None | Total estimated greenhouse gas emissions in kilograms $\text{CO}_2\text{e}$. |
| `carbon_calculation_notes`| `TEXT` | Yes | `NULL` | None | Audit notes explaining formula and factors applied. |
| `proof_of_delivery_receiver_name` | `VARCHAR(255)` | Yes | `NULL` | None | Identity of person signing for cargo receipt. |
| `proof_of_delivery_notes` | `TEXT` | Yes | `NULL` | None | Warehouse dock receipt #, bill of lading confirmation, or remarks. |
| `proof_of_delivery_at` | `DATETIME` | Yes | `NULL` | None | Timestamp of formal delivery acceptance. |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | None | Record creation timestamp. |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | None | Record update timestamp. |
| `deleted_at` | `TIMESTAMP` | Yes | `NULL` | Index | Soft deletion timestamp for audit retention. |

---

### 2.7. `transport_legs`
Individual sequential transport segments composing the multimodal freight journey.

| Column Name | Data Type | Nullable | Default | Constraints & Indexes | Business Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto-increment | Primary Key | Unique leg identifier. |
| `shipment_id` | `BIGINT UNSIGNED` | No | None | Foreign Key -> `shipments(id)` ON DELETE CASCADE | Parent shipment reference. |
| `sequence_number` | `INT` | No | `1` | None | Sequential order of the leg ($1, 2, 3, \dots$). |
| `transport_mode` | `VARCHAR(255)` | No | None | Index (`sea`, `land`, `air`) | Physical mode of transit. |
| `origin_location_id` | `BIGINT UNSIGNED` | No | None | Foreign Key -> `locations(id)` | Leg departure hub or facility. |
| `destination_location_id` | `BIGINT UNSIGNED` | No | None | Foreign Key -> `locations(id)` | Leg destination hub or facility. |
| `carrier_name` | `VARCHAR(255)` | No | None | None | Operating carrier (e.g. Maersk, DHL Freight, Lufthansa Cargo). |
| `transport_reference` | `VARCHAR(255)` | Yes | `NULL` | None | Vessel & Voyage #, Flight #, or Truck License Plate. |
| `planned_departure` | `DATETIME` | Yes | `NULL` | None | Scheduled departure timestamp. |
| `actual_departure` | `DATETIME` | Yes | `NULL` | None | Recorded physical departure timestamp. |
| `estimated_arrival` | `DATETIME` | Yes | `NULL` | None | Scheduled leg arrival timestamp. |
| `actual_arrival` | `DATETIME` | Yes | `NULL` | None | Recorded physical arrival timestamp. |
| `distance_km` | `DECIMAL(10, 2)` | No | `0.00` | None | Calculated or verified distance in kilometers. |
| `distance_source` | `VARCHAR(255)` | No | `'calculated_haversine'` | None | Source: `calculated_haversine`, `manual_operator_override`, `standard_corridor`. |
| `emission_factor_id` | `BIGINT UNSIGNED` | Yes | `NULL` | Foreign Key -> `carbon_emission_factors(id)` ON DELETE SET NULL | Active emission factor applied for leg carbon calc. |
| `leg_estimated_co2e_kg` | `DECIMAL(10, 2)` | No | `0.00` | None | Leg emissions calculated: $(\text{t} \times \text{km} \times \text{factor}) / 1000$. |
| `status` | `VARCHAR(255)` | No | `'PENDING'` | Index (`PENDING`, `IN_TRANSIT`, `COMPLETED`, `CANCELLED`) | Operational state of the transport segment. |
| `notes` | `TEXT` | Yes | `NULL` | None | Segment-specific transit notes. |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | None | Record creation timestamp. |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | None | Record update timestamp. |

---

### 2.8. `tracking_events`
Milestone tracking events recorded sequentially to construct the shipment timeline and Leaflet map trajectory.

| Column Name | Data Type | Nullable | Default | Constraints & Indexes | Business Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto-increment | Primary Key | Unique tracking event ID. |
| `shipment_id` | `BIGINT UNSIGNED` | No | None | Foreign Key -> `shipments(id)` ON DELETE CASCADE | Parent shipment reference. |
| `transport_leg_id` | `BIGINT UNSIGNED` | Yes | `NULL` | Foreign Key -> `transport_legs(id)` ON DELETE SET NULL | Associated transport leg if event occurred during a specific segment. |
| `event_type` | `VARCHAR(255)` | No | None | Index | Machine-readable event tag (e.g. `DEPARTED`, `ARRIVED_AT_TERMINAL`, `CUSTOMS_CLEARED`, `DELIVERED`). |
| `status_after_event` | `VARCHAR(255)` | No | None | None | Resulting shipment lifecycle status after this milestone. |
| `event_time` | `DATETIME` | No | None | Index | Real-world occurrence timestamp of the milestone. |
| `location_name` | `VARCHAR(255)` | No | None | None | Display name of the facility or city. |
| `latitude` | `DECIMAL(10, 7)` | Yes | `NULL` | None | WGS84 coordinate for map marker placement. |
| `longitude` | `DECIMAL(10, 7)` | Yes | `NULL` | None | WGS84 coordinate for map marker placement. |
| `description` | `TEXT` | No | None | None | Human-readable event narrative shown to users. |
| `recorded_by_user_id` | `BIGINT UNSIGNED` | Yes | `NULL` | Foreign Key -> `users(id)` ON DELETE SET NULL | Operator or admin user who logged the event. |
| `is_customer_visible` | `BOOLEAN` | No | `true` | None | Visibility toggle (allows internal operational notes to remain operator-only). |
| `proof_notes` | `TEXT` | Yes | `NULL` | None | Seal number, container inspection notes, or receiver signature. |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | None | System entry timestamp. |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | None | System update timestamp. |

---

### 2.9. `delivery_exceptions`
Captures delays, weather interruptions, cargo damages, customs holds, and operational anomalies.

| Column Name | Data Type | Nullable | Default | Constraints & Indexes | Business Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto-increment | Primary Key | Unique exception identifier. |
| `shipment_id` | `BIGINT UNSIGNED` | No | None | Foreign Key -> `shipments(id)` ON DELETE CASCADE | Parent shipment reference. |
| `transport_leg_id` | `BIGINT UNSIGNED` | Yes | `NULL` | Foreign Key -> `transport_legs(id)` ON DELETE SET NULL | Associated segment where disruption occurred. |
| `category` | `VARCHAR(255)` | No | None | Index | Category: `weather_disruption`, `transport_delay`, `damaged_cargo`, `missing_documentation`, `customs_clearance_issue`, `failed_delivery_attempt`, `other_operational_issue`. |
| `severity` | `VARCHAR(255)` | No | `'medium'` | Index (`low`, `medium`, `high`, `critical`) | Disruption impact severity level. |
| `description` | `TEXT` | No | None | None | Detailed factual description of the disruption. |
| `action_taken` | `TEXT` | Yes | `NULL` | None | Corrective actions executed (e.g. vessel rerouting, expedited document submission). |
| `assigned_operator_id` | `BIGINT UNSIGNED` | Yes | `NULL` | Foreign Key -> `users(id)` ON DELETE SET NULL | Operator tasked with exception mitigation. |
| `is_resolved` | `BOOLEAN` | No | `false` | Index | Resolution status flag. |
| `resolved_at` | `DATETIME` | Yes | `NULL` | None | Timestamp of formal exception resolution. |
| `resolution_notes` | `TEXT` | Yes | `NULL` | None | Post-incident resolution summary. |
| `impact_on_eta_hours` | `INT` | No | `0` | None | Estimated schedule delay added to shipment ETA in hours. |
| `is_customer_visible` | `BOOLEAN` | No | `true` | None | Whether the exception is shown on public tracking. |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | None | Exception logging timestamp. |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | None | Exception modification timestamp. |

---

### 2.10. `audit_logs`
Provides an immutable compliance trail of state modifications and sensitive operational actions.

| Column Name | Data Type | Nullable | Default | Constraints & Indexes | Business Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto-increment | Primary Key | Unique audit entry identifier. |
| `user_id` | `BIGINT UNSIGNED` | Yes | `NULL` | Foreign Key -> `users(id)` ON DELETE SET NULL | System user initiating the action (`NULL` for system automated events). |
| `action` | `VARCHAR(255)` | No | None | Index (`CREATE`, `UPDATE`, `STATUS_CHANGE`, `LEG_UPDATE`, `EXCEPTION_LOGGED`, `EXCEPTION_RESOLVED`, `DELIVERY_CONFIRMED`, `API_ACCESS`) | High-level event category. |
| `auditable_type` | `VARCHAR(255)` | No | None | Index | Fully qualified model class name (e.g. `App\Models\Shipment`). |
| `auditable_id` | `BIGINT UNSIGNED` | No | None | Index | Target entity primary key ID. |
| `old_values` | `JSON` | Yes | `NULL` | None | JSON snapshot of entity attributes before modification. |
| `new_values` | `JSON` | Yes | `NULL` | None | JSON snapshot of modified entity attributes. |
| `ip_address` | `VARCHAR(45)` | Yes | `NULL` | None | IPv4 or IPv6 address of the originating HTTP request. |
| `user_agent` | `TEXT` | Yes | `NULL` | None | Browser user-agent header string. |
| `description` | `VARCHAR(255)` | No | None | None | Concise audit narrative for administrative review. |
| `created_at` | `TIMESTAMP` | No | `CURRENT_TIMESTAMP` | Index | Immutable timestamp of the audit entry. |

---

## 3. Database Integrity & Design Rules

1. **Service Level vs Status Decoupling**:
   - `service_levels` determines the *delivery contract* (`DOOR_TO_DOOR`, `DOOR_TO_PORT`, `PORT_TO_PORT`, `B2B`).
   - `shipments.current_status` tracks *where the cargo is in its lifecycle*.
   - Port-to-Port shipments reaching `ARRIVED_AT_DESTINATION_TERMINAL` transition into `AWAITING_CUSTOMER_PICKUP` or `CUSTOMS_CLEARANCE`, **never automatically to `DELIVERED`**.
2. **Delivery Proof Mandate**:
   - Transition to `DELIVERED` strictly requires non-empty `proof_of_delivery_receiver_name` and records `proof_of_delivery_at`.
   - B2B shipments require dock receipt verification or receiving officer signature.
3. **Carbon Calculation Determinism**:
   - Leg emissions = $\frac{\text{Weight (t)} \times \text{Distance (km)} \times \text{Emission Factor (g/t-km)}}{1000}$.
   - Shipment emissions = $\sum \text{Leg Emissions}$.
   - All factors retain scientific citations (`GLEC Framework v3.0`, `IMO MEPC.308(73)`).

