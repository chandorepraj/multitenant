# Multi-Tenant CRM (Laravel)

A modern multi-tenant Customer Relationship Management (CRM) application built with Laravel. The application allows multiple organizations (tenants) to manage customers, leads, tasks, notes, team members, and reports while keeping each organization's data completely isolated.

## Features

### Multi-Tenancy

* Organization (Tenant) registration
* Multiple organizations per user
* Organization selection after login
* Tenant-based data isolation

### Authentication & Team Management

* User authentication
* Invitation-based team onboarding
* Email invitations for new and existing users
* Existing users can join multiple organizations
* Team member management
* Role-based authorization using Laravel Policies

### Customer Management

* Create, update and delete customers
* Customer-specific tasks
* Customer notes
* Customer reports
* CSV & PDF export

### Lead Management

* Create, update and delete leads
* Lead status tracking
* Lead-to-Customer conversion
* Lead tasks
* Lead notes
* Lead reports
* CSV & PDF export

### Task Management

* Tasks linked to Leads or Customers
* Assign tasks to team members
* My Tasks dashboard
* Due date tracking
* Task status management

### Activity Log

* Customer creation
* Lead creation
* Lead status changes
* Lead conversion
* Task and note activities

### Dashboard

* Customer count
* Lead count
* Team member count
* Pending tasks
* Today's tasks
* Recent activity

### Global Search

Search across:

* Customers
* Leads

### Reports

#### Lead Reports

* Date range filter
* Status filter
* Summary statistics
* CSV export
* PDF export

#### Customer Reports

* Date range filter
* Customer name filter
* Company filter
* Created By filter
* Summary statistics
* CSV export
* PDF export


## Testing

The application includes comprehensive Feature Tests using PHPUnit to ensure reliability across authentication, authorization, validation, and multi-tenant data isolation.

### Customer Tests

- Authentication tests
- Tenant middleware tests
- Role-based authorization tests
- Customer CRUD tests
- Validation tests
- Multi-tenant data isolation tests

### Lead Tests

- Authentication tests
- Tenant middleware tests
- Role-based authorization tests
- Lead CRUD tests
- Validation tests
- Multi-tenant data isolation tests

### Test Coverage

The current test suite verifies:

- Guest access restrictions
- Tenant selection middleware
- Role-based permissions (Admin, Manager, Sales, Support)
- CRUD operations
- Form validation
- Database assertions
- Multi-tenant security
- Authorization policies


## Technology Stack

* PHP 8.x
* Laravel 12
* MySQL
* Blade
* Bootstrap
* DomPDF
* Git
* GitHub

