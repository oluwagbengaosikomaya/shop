# Database Schema - Gift Shop

## All Tables Created Successfully ✅

### 1. **users**
- id (primary key)
- name
- email (unique)
- email_verified_at
- password
- is_admin (boolean, default: false)
- remember_token
- created_at
- updated_at

### 2. **products**
- id (primary key)
- name
- price (integer)
- stock (integer, default: 0)
- image (string)
- description (text, nullable)
- created_at
- updated_at

### 3. **orders**
- id (primary key)
- user_id (foreign key to users, nullable)
- customer_name
- customer_email
- customer_phone (nullable)
- total (decimal 10,2)
- status (default: 'pending')
- created_at
- updated_at

### 4. **order_items**
- id (primary key)
- order_id (foreign key to orders, cascade on delete)
- product_id (foreign key to products, cascade on delete)
- product_name
- quantity (integer)
- price (decimal 10,2)
- created_at
- updated_at

### 5. **cache** (Laravel system table)
- key (primary key)
- value
- expiration

### 6. **cache_locks** (Laravel system table)
- key (primary key)
- owner
- expiration

### 7. **jobs** (Laravel queue table)
- id (primary key)
- queue
- payload
- attempts
- reserved_at
- available_at
- created_at

### 8. **job_batches** (Laravel batch jobs)
- id (primary key)
- name
- total_jobs
- pending_jobs
- failed_jobs
- failed_job_ids
- options
- cancelled_at
- created_at
- finished_at

### 9. **failed_jobs** (Laravel failed jobs)
- id (primary key)
- uuid (unique)
- connection
- queue
- payload
- exception
- failed_at

## Seeded Data

### Users:
1. **Admin User**
   - Email: admin@shop.com
   - Password: password
   - Role: Admin

2. **Customer User**
   - Email: customer@shop.com
   - Password: password
   - Role: Customer

### Products:
1. Love Handband - ₦5,000 (Stock: 10)
2. Pink Bag - ₦2,500 (Stock: 15)
3. Hanging Gift - ₦5,000 (Stock: 8)
4. Special Gift Box - ₦8,000 (Stock: 12)
5. Clothing - ₦8,000 (Stock: 20)
6. Bear - ₦4,000 (Stock: 5)

## Relationships

- **User → Orders** (One to Many)
- **Order → OrderItems** (One to Many)
- **Order → User** (Belongs To)
- **OrderItem → Order** (Belongs To)
- **OrderItem → Product** (Belongs To)

## Migration Status: ✅ All Complete
All 9 migrations have been successfully run.
