# BookBus — Bus Reservation Engine

BookBus is a Laravel-based bus reservation platform designed for a single transportation company.
The system provides an intelligent booking engine with segmented pricing, seat reservation, trip management, and administrative operations.

The project focuses on scalable backend architecture, relational database modeling, and real-world transportation business logic.

---

## Application Preview

Screenshots and UI previews available below.

---

## Product Overview

The application was designed to simulate a real-world reservation system for a transportation company managing:

* Bus fleets
* Drivers and employees
* Routes and schedules
* Segment-based pricing
* Passenger reservations
* Administrative operations

Unlike traditional booking systems, the platform uses an independent pricing model where each route segment has its own dedicated fare.

Example:

* Casablanca → Marrakech = 120 MAD
* Casablanca → Settat = 50 MAD
* Settat → Marrakech = 70 MAD

Prices are independently defined and not automatically cumulative.

---

## Core Features

### Reservation Engine

* Search available trips
* Segment-based booking system
* Seat selection system
* Passenger reservation management
* Real-time seat availability

### Smart Pricing System

* Independent segment pricing
* Dynamic fare calculation
* Premium bus pricing management
* Promotional discount support

### Fleet & Driver Management

* Bus assignment management
* Driver assignment system
* Bus maintenance status tracking
* Driver availability validation

### Administrative Dashboard

* Trip creation and cancellation
* Route and schedule management
* Fleet monitoring
* Reservation tracking
* Business statistics overview

### Customer Features

* Ticket booking
* Booking cancellation
* Loyalty points integration
* Promo code support
* Email confirmation system

---

## Tech Stack

### Backend

* Laravel
* PHP
* Eloquent ORM
* MVC Architecture

### Database

* MySQL

### Frontend

* Blade Templates
* HTML5
* CSS3
* JavaScript

### Tools

* Git & GitHub
* Composer

---

## Architecture Overview

The application follows Laravel’s MVC architecture:

### Models

Handle:

* Database relationships
* Business logic
* Fare calculations
* Reservation validation

### Controllers

Handle:

* HTTP requests
* Booking workflows
* Admin operations
* Search logic

### Views

Handle:

* User interface rendering
* Reservation forms
* Dashboard visualization

---

## Database Design

Main entities implemented:

* Users
* Employees
* Cities
* Stations
* Buses
* Routes
* Stops
* Segments
* Fares
* Schedules
* Trips
* Bookings
* Passengers
* Assignments

---

## Key Technical Highlights

* Complex relational database modeling
* Segment-based pricing architecture
* Eloquent ORM relationships
* Seat availability management
* Route validation system
* Business rule implementation
* Admin management workflows
* Real-world transportation logic

---

## Business Rules Implemented

* A bus cannot be assigned to multiple trips simultaneously
* Drivers cannot exceed daily driving limits
* Premium buses apply additional pricing
* Invalid travel segments are automatically rejected
* Seat capacity validation before booking

---

## Challenges Faced

* Designing the segmented pricing system
* Managing complex Eloquent relationships
* Handling reservation validation logic
* Synchronizing bus capacity and bookings
* Implementing scalable route architecture

---

## Performance & Best Practices

* Clean Laravel MVC structure
* Reusable Eloquent relationships
* Validation and secure request handling
* Maintainable business logic
* Responsive user interface
* Organized codebase structure

---

## Future Improvements

* Online payment integration
* QR ticket generation
* Real-time bus tracking
* Multi-company support
* Mobile application
* Notification system
* API integration

---

## Project Structure

```bash
app/
├── Models/
├── Http/Controllers/
├── Services/
├── Policies/

database/
├── migrations/
├── seeders/

resources/
├── views/
```

---


## Application Preview
<img width="1763" height="2534" alt="Capture d’écran_23-5-2026_184018_127 0 0 1" src="https://github.com/user-attachments/assets/59ae5dd7-861f-4500-99be-22ac2d9a6dc6" />
<img width="1763" height="1362" alt="Capture d’écran_23-5-2026_17535_127 0 0 1" src="https://github.com/user-attachments/assets/130cf769-3987-4dbd-9d48-2507a7d3b068" />
<img width="1763" height="938" alt="Capture d’écran_23-5-2026_174836_127 0 0 1" src="https://github.com/user-attachments/assets/0cb29a97-447f-4d05-92a5-66d5490d85bf" />
<img width="1763" height="1214" alt="Capture d’écran_23-5-2026_175342_127 0 0 1" src="https://github.com/user-attachments/assets/9c086adf-4d5a-4fd5-806e-ea528993231b" />
<img width="740" height="861" alt="Capture d’écran 2026-05-23 181436" src="https://github.com/user-attachments/assets/e2b8f7cc-67dd-48e8-8bfe-b2969970566b" />
<img width="1763" height="971" alt="Capture d’écran_23-5-2026_181655_127 0 0 1" src="https://github.com/user-attachments/assets/c19d62c2-b93e-4954-aa21-52acd02e097e" />
<img width="1763" height="1362" alt="Capture d’écran_23-5-2026_193329_127 0 0 1" src="https://github.com/user-attachments/assets/eeca37e1-ddbc-4be4-8088-f5cf7f3469bc" />
<img width="1763" height="2534" alt="Capture d’écran_23-5-2026_184018_127 0 0 1" src="https://github.com/user-attachments/assets/510da0a2-64da-43d1-b4ac-07d74aedaefc" />


**Khadija Abirat**
Full-Stack Developer
Passionate about scalable web applications, backend architecture, and real-world business systems.
