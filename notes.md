
### Additional questions

You do not need to actually implement support for the below items, just have an idea for how the app would
be changed to support each one. We will discuss them during a subsequent code review session.

#### Persistence

- How would you add a persistent storage layer such that the app could be restarted without losing counter states?
- What storage technology would you suggest?

| user_id | team_id | counter |
|---------|---------|---------|
| 15      | 24      | 0       |

```sql
create table teams
(
  team_id int not null primary key auto_increment
) engine = InnoDB;

create table counters
(
  id      int not null primary key auto_increment,
  user_id int not null,
  team_id int not null,
  counter int not null default 0,
  unique team_user (team_id, user_id)
) engine = InnoDB;

```

#### Fault tolerance

- How would you design the app in order to make the functionality be available even if some parts of the underlying hardware systems were to
  fail?

Add Cache for reading, duplication db master/slave

#### Scalability

- How would you design the app in order to ensure that it wouldn't slow down or fail if usage increased
  by many orders of magnitude? what if this turned into a global contest with 10x, 100x, 1000x the
  number of teams and traffic?
- Does your choice of persistence layer change in this scenario?

Add indexes, we can introduce denormalized total_counter field and calculate it in a background or atomically change every time
