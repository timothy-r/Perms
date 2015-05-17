create table perm(subject text, object text, value text);

-- store events, time is the event created timestamp not from when it is stored
create table events(subject text, object text, time integer, key text, action text);