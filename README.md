# Business Portal - Empowering Tomorrow's Entrepreneurs

interactive-AliSher-finalcode is an entrepreneur portal with business resources and community features.

![PHP](https://img.shields.io/badge/PHP-2563EB?style=flat-square) ![MySQL](https://img.shields.io/badge/MySQL-0F172A?style=flat-square)

## Overview

The data model covers `agent_profiles`, `business_categories`, `business_ideas`, `business_resources`, `business_templates`, `events`, `feedback`, `forum_replies`, `forum_threads`, `opportunities`, `private_messages`, `tutorials`, and related records.

## Application areas

| Area | Pages in the repository |
| --- | --- |
| Admin | [`admin/admin_dashboard.php`](admin/admin_dashboard.php), [`admin/admin_manage_events.php`](admin/admin_manage_events.php), [`admin/admin_manage_tutorials.php`](admin/admin_manage_tutorials.php), [`admin/admin_users.php`](admin/admin_users.php), [`admin/mentor_profile.php`](admin/mentor_profile.php) |
| Agent | [`agent/agent_categories.php`](agent/agent_categories.php), [`agent/agent_dashboard.php`](agent/agent_dashboard.php), [`agent/agent_feedbacks.php`](agent/agent_feedbacks.php), [`agent/agent_ideas.php`](agent/agent_ideas.php), [`agent/agent_messages.php`](agent/agent_messages.php) |
| Root Pages | [`login.php`](login.php), [`register.php`](register.php) |
| User | [`user/event_details.php`](user/event_details.php), [`user/forum.php`](user/forum.php), [`user/forum_discussion.php`](user/forum_discussion.php), [`user/idea_details.php`](user/idea_details.php), [`user/ideas.php`](user/ideas.php) |

## Getting started

### 1. Get the project

```bash
git clone https://github.com/nasirabbas827/interactive-AliSher-finalcode.git
cd interactive-AliSher-finalcode
```

### 2. Configure PHP and the database

1. Use a local PHP server with MySQL or MariaDB, such as XAMPP.
2. Import [`DatabaseFile/entrepreneur_portal_ali.sql`](DatabaseFile/entrepreneur_portal_ali.sql) into your local database server.
3. Set your local database connection details in [`admin/config.php`](admin/config.php), [`agent/config.php`](agent/config.php), [`config.php`](config.php), [`user/config.php`](user/config.php).
4. Keep the existing asset and upload directory paths intact.

### 3. Start the application

With the database running, start a local development server from the repository root:

```bash
php -S 127.0.0.1:8000
```

Open http://127.0.0.1:8000/index.php. If using Apache/XAMPP instead, place the project in its document root and open the matching localhost folder.

## Repository guide

| Path | Purpose |
| --- | --- |
| [`DatabaseFile`](DatabaseFile) | Database scripts |
| [`config.php`](config.php) | Application/database configuration |
| [`index.php`](index.php) | Web entry page |

## Development notes

These setup instructions are based on the repository source. The application has not been executed as part of this documentation update; verify dependencies and configuration in your local environment.
