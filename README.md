Permissions store
=============================

[![Build Status](https://travis-ci.org/timothy-r/Perms.png?branch=master)](https://travis-ci.org/timothy-r/Perms)

A permissions store service which stores permissions, eg. read, write, admin for role and object pairs. 

Glossary:
* **Role** eg. the name of the role with this permission
* **Object** eg. a bug tracker issue or article, the identifiers are opaque to the store and need to be unique across all its clients
* **Perm** is a string, eg. *read* or *write*, the perm store simply stores these values, the clients give them meaning

*In the uri examples the id parameters contain identifiers, role and object are literals.*

API
---

Set 
---
PUT /role/{name}/object/{id}/{perm} 

Sets the *admin* perm for *role 'article-admin'* on *article 99*

`curl -X PUT http://perms-store.net/role/article-admin/object/article:99/admin`

Response:
* 200 or 201 for success

Test
----
HEAD /role/{name}/object/{id}/{perm} 

Test if *role user* may *read* *article 99*

`curl -X HEAD http://perms-store.net/role/user/object/article:99/read`

Response:
* 200 if perm exists
* 404 if perm does not exist

Retrieve
--------
GET /role/{name}/object/{id}/{perm} 

Test if *user 1* may *read* *article 99*

`curl -X GET http://perms-store.net/role/user:1/object/article:99/read`

Response:
* 200 and {"role":"user:1","object":"article:99"}
* 404 if perm does not exist

Retrieve all 
------------
GET /role/{name}/object/{id} 

Get all the perms *user 1* has on *article 99*

`curl -X GET http://perms-store.net/role/user:1/object/article:99`

Response:
* 200 and {"perms":["read"],"role":"user:1","object":"article:99"} for success
* 404 when no perms exist

Remove 
------
DELETE /role/{name}/object/{id}/{perm} 

Removes *write* perms from *user 1* on *article 99*

`curl -X DELETE http://perms-store.net/role/user:1/object/article:99/write`

Response:
* 200 if successfully removed, ie. the perm is no longer set

Remove all 
----------
DELETE /role/{name}/object/{id} 

Removes all perms from *user 1* on *article 99*

`curl -X DELETE http://perms-store.net/role/user:1/object/article:99`

Response:
* 200 if successfully removed, ie. there are no perms set for this pair any longer

Retrieve all for role
------------------------
GET /role/{name} 

Get a json object containing all the *objects* and their associated perms for *user 1*

`curl -X GET http://perm-store.net/role/user:1`

Response:
* 200 and [{"perms":["read"],"role":"user:1","object":"article:99"},{"perms":["write"],"role":"user:1","object":"article:49"}]

Retrieve all for role with perm
---------------------------------
GET /role/(name}/{perm} 

Get a json object containing all the *objects* with admin perm for *user:1*

`curl -X GET http://perm-store.net/role/user:1/admin`

Response:
* 200 and [{"role":"user:1","object":"article:49"}]

Retrieve all for object
-----------------------
GET /object/{name} 

Get a json object containing all the *roles* and their associated perms for *article:99*

`curl -X GET http://perm-store.net/object/article:99`

Response:
* 200 and [{"perms":["read"],"object":"article:99","role":"user:1"}]

Retrieve all for object with perm
---------------------------------
GET /object/{name}/{perm} 

Get a json object containing all the *roles* with write perm for *article:99*

`curl -X GET http://perm-store.net/object/article:99/write`

Response:
* 200 and [{"object":"article:99","role":"user:1"}]

Discussion
==========

Benefits
--------

Benefits of this api over putting multiple perms in a single request is that we don't need to test if the incoming object is fresh using ETags. Each request is atomic in that sense.

This is a simple store api, further perms functionality can be built on top of this with code that can filter the id strings.

It would be better to offer a filter api to the perm store than ask clients to filter out unwanted results.

Missing functionality
---------------------

Since the store treats the role and object identifiers as opaque strings we can't obtain all the objects of type article which a role has read permission on. Clients will need to retrieve all objects that a role has the read perm on and then filter out ones that are not articles, since only the clients know what the identifers represent. If, for example, all article identifiers contain the string 'article' then we could add a filter interface to do this on the server not the client.

`curl -X GET http://perm-store.net/role/user:1/view?object=article`

In the store service it could use the object query parameter as a wild card match on the object identifier string.

Caching
=======

How well can this api be cached by a HTTP cache such as varnish?

For the endpoints which treat perms as individual resources a varnish cache can be configured to handle caching and purging correctly on its own without any intervention from the perms service application.

* PUT /role/{name}/object/{id}/{perm} 
* HEAD /role/{name}/object/{id}/{perm}
* GET /role/{name}/object/{id}/{perm}
* DELETE /role/{name}/object/{id}/{perm}
* GET /role/{name}/object/{id}
* DELETE /role/{name}/object/{id} 

Implementing these endpoints will require the perms store to purge an external cache programmatically. Varnish may be configurable to be able to do this with vcl when making a PUT or DELETE request, but the docs aren't that clear.

* GET /role/{name} 
* GET /role/(name}/{perm} 
* GET /object/{id} 
* GET /object/{id}/{perm} 

