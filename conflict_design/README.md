## Calendar Conflicts

One of the core pieces of functionality in our system is our calendaring module. When a user loads a calendar, the calendar displays all the visits that are scheduled for the selected client within the given time period. (A “visit” is a scheduled time for a caregiver to give care to a client, similar to an in-home doctor’s visit.) In addition to the visits, the calendar also displays any “conflicts” with the given visits. There are many types of potential conflicts:

- A client has overlapping visits scheduled
- A caregiver has overlapping visits scheduled
- A caregiver is scheduled for more than 40 hours of care (meaning the agency would have to pay overtime)
- A caregiver is assigned to give care to a client with whom the caregiver is incompatible (for instance, the client has a dog and the caregiver is allergic to dogs)
- … and other types of conflicts

Currently, the server has to determine these (and any other) conflicts every time a calendar is loaded:

1. user loads a calendar, which triggers an API call to load visits
2. server gathers visits
3. server calculates conflicts on each visit
4. server sends API response
5. and finally, the visits appear on the user's calendar

For large systems, step 3 quickly becomes a slow operation. Your assignment is to propose a design that alleviates the slowness on large systems. We are not looking for code; we are looking for a high-level discussion about a method of solving this problem that would result in a better experience for our users.

After you come up with your design, we will ask you to explain your solution, then we will discuss the solution further. Your solution will be judged using the following criteria:

- user experience (don't burden the user with extra unnecessary steps)
- potential execution time speedup
- memory usage
- ease of implementation
- maintainability (will this solution still work as the system grows and we add additional types of conflicts?)
- infrastructure (what additional infrastructure requirements does this solution introduce?)

Good luck!

# Suggestions

I would be interested in discussing the expected workflow for Visit creation and conflict resolution.

For example the system could be designed such that Clients request Visits but are not concerned with potential conflicts or resolutions, e.g. the Client can request a specific Caregiver for a given date / time, but a user in an administrative role will be responsible for filling the request. In such a system, preventing conflicts could be simplified by filtering the available Caregivers which could be assigned to fill a request. This might simplify a lot of these challenges, but isn't as interesting of an answer.

Given a legacy system in which Visits are scheduled without the opportunity to prevent conflicts, I would lean towards designing a system which pre-calculates the conflicts and stores them persistently. Depending on the user experience necessary, a given visit could have conflicts calculated on creation or behind-the-scenes in a queue or cron. This should speed up the user experience dramatically.

I would set it up with a consistent interface designated by a Conflict parent object, maybe abstract, and extend various rules from it as it makes sense to abstract the actual calculation logic for each type. Overlapping Visits can be constrained to reduce time complexity (client and caregiver), there's no need to compare each visit to Visits in other weeks, for example. The need for a Conflict type which would flag all Visits for a Caregiver in the Overtime case suggests a need for a joining table, rather than just a "Visit X conflicts with Visit Y" one-to-one.

I'm not sure if y'all want me to go into the nitty gritty of the tables, but I'm initially expecting a Visit object would have a primary key, Client and Caregiver foreign keys, start/end times, and misc admin and business logic fields as necessary.  Making sure the table is nicely indexed would be critical as well, definitely keys for Client, Caregiver, maybe compound with times as well.  The Conflicts table might have it's own primary key, a type field, and a many-to-many joining table to the applicable Visits.

By way of examples:

> If two Visits overlap, you might have Visit 5 and Visit 6 double booking a Client.  So you would have an entry in Conflicts with an ID of 1 and type of Double-booked Client, and then the joining table would have entries for 1/5 and 1/6.  This should be relatively quick to lazy load along with visits for return to the front end.

> If a Caretaker has visits scheduled which push them over the given hourly limit, Visits 7,8,9, and 10 in a week, an entry in Conflicts with ID 2 and type of Overtime, and corresponding entries in the joining table (2/7,2/8,2/9,2/10) make it easy to flag all of them for the calendar display.

Because of the variation in logic necessary to clear conflicts, I was initially thinking a Command pattern would make sense, but I think we could get away with a pretty basic Rules pattern: [An Oldie But Goodie](https://www.michael-whelan.net/rules-design-pattern/).