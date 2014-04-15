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

Set a perm 
---------------------
PUT /subject/{$id}/object/{$id}/{$perm} 

Sets the perm for this pair

Test a single perm 
---------------------------------

HEAD /subject/{$id}/object/{$id}/{$perm} 

Tests if perm is set for the pair

Retrieve all perms
------------------
GET /subject/{$id}/object/{$id} 

Returns a json object containing the perm names for this pair

Remove a perm
--------------------
DELETE /subject/{$id}/object/{$id}/{$perm} 

Removes perm from this pair

Remove all perms
----------------
DELETE /subject/{$id}/object/{$id} 

Removes all perm names for the pair

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


