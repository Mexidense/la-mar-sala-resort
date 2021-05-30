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
