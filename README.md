# La Mar Salá Resort

## Description

This exercise refers to the computer system of a holiday residence or resort. Regarding the software system, the resort has been decided to start with a new system. In the first stage, you'll focus to develop a booking management system to book rooms.

After interviews, requirement inputs and researching about the resort domain. The resort domain has the following properties:

- The rooms will be identified with a number, the first digit identifies the floor of the bedroom and, the rest with the number of the room.
- residents will be identified with their National documentation ID (DNI), full name, birthdate and gender.

User cases will be the following:

- When a resident enters to the resort the system will take its data and will add to resort with the check-in date and check-out date.
- When a resident wants to leave the room early, the system will look for a new booking with a new date.
- When a resident wants to change the room, the system will set the check-out date to the current book and to create a new booking with new check-in/out dates.
- Every time the system makes a new booking, the system will identify the new booking with a new auto-increment number with the resident's data, booking's check-in/out dates.
- The system will have a report subsystem:
  - resident list who are in the resort with its booked room, and the check-in/out date.
  - Available room list on a specific date.
  - Average age list by gender.
  
Notes:
- The data structure will be an array. The number of array's element will be 5 by default.

---

# Solution

First of all, as you may know, this project comes from Programming Methodologies subject of the first course in Computer engineering bachelor degree at University of Almeria.
Originally, this exercise was mainly created by [Antonio Becerra](https://twitter.com/ualabecerra) and others, Antonio is one of awesome teachers in Computer department in this amazing university.
That project came from Java language, and I had to make some modification on the Test part.

The approach applied was TDD ([Test-Driven Development](https://martinfowler.com/bliki/TestDrivenDevelopment.html)) that it was proposed in the subject above mentioned and, I've added one more: DDD ([Domain-Driven Design](https://martinfowler.com/bliki/DomainDrivenDesign.html)) that I'm learning and then, I wanted to use this project to put into practice my knowledge. Feel free to add any comment or issue within repository.
DDD approach needs some touches which will be applied as soon as possible.

## Installation 

- Clone repository:
```batch
git clone git@github.com:Mexidense/la-mar-sala-resort.git resort && cd resort
```

- Install packages and libraries:
```batch
composer install
```

## Usage

- Run unit tests:
```batch
vendor/bin/phpunit


Result:
PHPUnit 9.5.4 by Sebastian Bergmann and contributors.

..................                                                18 / 18 (100%)

Time: 00:00.012, Memory: 6.00 MB

OK (18 tests, 91 assertions)

```
