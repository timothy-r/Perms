Permissions store
=============================

[![Build Status](https://travis-ci.org/timothy-r/Perms.png?branch=master)](https://travis-ci.org/timothy-r/Perms)

A permissions store service which stores permission keys, eg. read, write, admin for subject, object pairs. The store allows associating any arbitrary keys with a pair of opaque identifier strings.

Glossary:
* **Subject** is a User or UserGroup, the identifiers are opaque to the store and need to be unique for all its clients
* **Object** is a bug tracker issue or article, the identifiers are opaque to the store and need to be unique for all its clients
* **Perm** is a string, the perm store simply stores these values, the clients give them meaning

*In the uri examples the id parameters contain type and id information, subject and object are literals.*

API
---

Set 
---
PUT /subject/{$id}/object/{$id}/{$perm} 

Sets the *admin* perm for *user 1* on *article 99*

`curl -X PUT http://perms-store.net/subject/user:1/object/article:99/admin`

Response:
* 200 or 201 for success
* 500 for failure

Test
----
HEAD /subject/{$id}/object/{$id}/{$perm} 

Test if *user 1* may *read* *article 99*

`curl -X HEAD http://perms-store.net/subject/user:1/object/article:99/read`

Response:
* 200 if perm exists
* 404 if perm does not exist

Retrieve all 
------------
GET /subject/{$id}/object/{$id} 

Get all the perms *user 1* has on *article 99*

`curl -X GET http://perms-store.net/subject/user:1/object/article:99`

Response:
* 200 and ["read", "write", "admin"] for success
* 404 when no perms exist

Remove 
------
DELETE /subject/{$id}/object/{$id}/{$perm} 

Removes *write* perms from *user 1* on *article 99*

`curl -X DELETE http://perms-store.net/subject/user:1/object/article:99/write`

Response:
* 200 if successfully removed

Remove all 
----------
DELETE /subject/{$id}/object/{$id} 

Removes all perm names for the subject,object pair

Retrieve all for subject
------------------------
GET /subject/{$id} 

Returns a json object containing all the objects and their perm names for the subject eg all things a user has any permission on 

Retrive all for subject with perm
---------------------------------
GET /subject/($id}/{$perm} 

Returns a json object containing all the objects with this perm name for the subject, eg all things a user may admin 

Retrieve all for object
-----------------------
GET /object/{$id} 

Returns a json object containing all the subjects and perm names for this object, eg all things a user has any perm on

Retrieve all for object with perm
---------------------------------
GET /object/{$id}/{$perm} 

Returns a json object containing all the subjects with this perm name set for this object, eg all subjects who may read an issue

Benefits of this api over putting multiple perms in one request is that we don't need to test if the incoming object is fresh using ETags. Each request is atomic in that sense.

This is a simple store, further perms functionality can be built on top of this with code that can filter the id strings? So get all perms and filter out the ones you don't want?  

It would be better to offer a filter api to the perm store than ask clients to filter out unwanted results.

How to get all issues a user may view?
Can only get all things a user may view and filter out non-issues...
Add a filter to the store
GET /subject/($id}/view?object=issue

Caching
=======

For the 4 simple endpoints which treat perms as individual resources a varnish cache can be configured to handle caching and purging correctly

PUT /subject/{$id}/object/{$id}/{$perm} 
HEAD /subject/{$id}/object/{$id}/{$perm}
GET /subject/{$id}/object/{$id}/{$perm}
DELETE /subject/{$id}/object/{$id}/{$perm}

Implementing these endpoints requires the perms store to purge the cache programmatically

DELETE /subject/{$id}/object/{$id} 
GET /subject/{$id}/object/{$id} // get multiple perms in one request
PUT /subject/{$id}/object/{$id} // set muliple perms in one request

GET /subject/{$id} 
GET /subject/($id}/{$perm} 
GET /object/{$id} 
GET /object/{$id}/{$perm} 


