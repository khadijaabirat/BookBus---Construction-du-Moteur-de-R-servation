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

## Author

**Khadija Abirat**
Full-Stack Developer
Passionate about scalable web applications, backend architecture, and real-world business systems.
