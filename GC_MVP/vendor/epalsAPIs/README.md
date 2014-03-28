ePals API 
=========


Version
----

0.9

Markdown Reader
---- 
http://dillinger.io

Description
----

The ePals API allows you to create and manipulate the core objects in the community. Currently these objects include:

* Communities
        - add, update, addTenant

* Tenants
        - add, update

* Users (Teacher, Student, Parental)
        - add, update, delete, retrive
        - userExists, updatePassword, getGroups, getTeacherGroups
        - add roles
        - add,update,delete attributes
        - add,update, delete preferences
        
* Groups
        - add, update, delete
        - add member, add observer, add assistant

* TeachersGroups
        - add
        - add teacher, add student
        - remove teacher, remove student


* TeachersGroups
        - add
        - add users, remove users

* Profiles
        - create, update, delete

* Content
        - create, update, delete
        - search
* Event
        - create, update, delete 

The reference implementation for the objects is in php. Other languages are coming soon - python, objective-c, android, java, .net, and ruby. 


The main idea is that developers interact with the same object model to create exciting new learning experiences



Installation
--------------
For our php reference, we use composer to install dependencies. A little information on composer and how to install it can be found here: <a href="http://getcomposer.org/doc/00-intro.md">http://getcomposer.org/doc/00-intro.md</a>. You should install composer before proceeding. Once you're done you can then do this 

```sh
git clone https://<user>@epals.git.cloudforge.com/api.git
cd api/php
/usr/local/bin/composer install
```
And you're ready to go!

Some Examples
----------
###Communities

First step would be to create Community

```sh
$community = new Community();
$community->setName("test Community");
$community->setDescription("Community description");
$community->setSsorealm("http://test.epals.com/sso/");
$result1 = $community->add();
print_r(result1);

```

###Tenants

Then Create tenant:

```sh
$tenant = new Tenant();
$tenant->setDomain("epals.test.com");
$tenant->setEmailDomain("epals.mail.test.com");
$tenant->setPublished(false);
$tenant->setName("testtown");
$result2 = $tenant->add();
```

Add Tenant in community (by domain):

```sh
$result3 = $community->addTenant("anytown.epals.com");
```

###Users
```sh
$t = new Teacher();
$t->setFirstName("Albus");
$t->setLastName("Dumbledore");
$t->setExternalEmail("shahzad@mac.com");
$t->setUserId("albusdumbledore");
$t->setAccount("albusdumbldore@anytown.epals.com");
$t->setPassword("abracadabra");
$t->add();
```

Then we can add a student to the teacher

```sh
$s = new Student();
$s->setFirstName("Harry");
$s->setLastName("Potter");
$s->setExternalEmail("shahzadchaudhri@gmail.com");
$s->setUserId("harrypotter");
$s->setAccount("harrypotter@anytown.epals.com");
$s->setPassword("lily");
$s->add();
```
Note that users are always  identified by their account. In this case Albus Dumbledore is identified by albusdumbldore@anytown.epals.com and harry potter is harrypotter@anytown.epals.com. 

We use fully qualified account names to avoid namespace conflicts. There could be a harrypotter@epals.com as well as a harrypotter@neupals.com. For us these are two completely different people. 

Given the account, we can do more interesting things, like make the Albus Dumbledore Harry Potter's teacher. 
```sh
// instantiate a new dumbledore teacher object
$t = new Teacher("albusdumbldore@anytown.epals.com");

// add harry potter as a student
$t->addStudent("harrypotter@anytown.epals.com");
$t->update();
```

Setting user preferences
```sh
$up = new UserPreference("harrypotter@anytown.epals.com");
$up->add("Sport", "Quidditch");
$up->add("Animal", "White Owl");
$up->update();
```
Adding user Attributes
```sh
$ua = new UserAttribute("harrypotter@anytown.epals.com");
$ua->add("House", "Gryffindor");
$up->add("Trait", "Brave");
$up->update();
```

Then, you can do something like this to display all user houses and their favourite sport:
```sh
foreach ($users as $u) {
    $ua = new UserAttribute($u);
    $up = new UserPreference($u);
    $sport = $up->get("Sport");
    $house = $ua->get("House");
    print("$u->getFirtName(): House: $house -- Sport: $sport");
}
```

###Groups

Groups must exist within a community. You can specify the community when you're creating the group, something like this: 
```s
$g = new Group("anytown.epals.com");
$g->setName("Dumbledore's Army");
$g->addStudentToGroup("harrypotter@anytown.epals.com");
$g->addTeacherToGroup("albusdumbledore@anytown.epals.com");
$g->add();
```

###TeachersGroups

Add Teachers Group

```s
$g1 = new TeachersGroup();
$g1->setName("_Matias_Gray_Group");
$g1result = $g1->add("anytown.epals.com");
```

Add Teacher to Teachers Group

```s
$g1 = new TeachersGroup("anytown.epals.com","_Matias_Gray_Group");
$g1->addTeacherToGroup("teacher@anytown.epals.com");
$g1->addStudentToGroup("student@anytown.epals.com");

```

###Profiles

Users in the system can have profiles. Each profile has attributes. Here's an example of adding a profile for a user.

```s
$p = new Profile();
$p->setAccount("harrypotter@epals.com");
$p->setDescription("My name is harry potter. I like to play Quidditch and make an effort at becoming a good magician. I'm looking for information on my parents.");
$p->setCountry("GB");
$p->setCity("London");
$p->setName("Harry Potter");
$p->setZip("E17");
$res = $p->add();
```

And updating a profile is as you'd expect, here we're updating Harry's zip from E17 to SW1:

```s
$p = new Profile("harrypotter@epals.com");
$p->setZip("SW1");
$res = $p->update();
```

###Content

As of now the content API is rudimentary. It allows you to add and get. The query syntax for the get is basic, but will improve as we develop. Here's an example of adding content.  

```s
$text = "I met a traveller from an antique land\r\n
Who said: \"Two vast and trunkless legs of stone\r\n
Stand in the desert. Near them on the sand,\r\n
Half sunk, a shattered visage lies, whose frown\r\n
And wrinkled lip and sneer of cold command\r\n
Tell that its sculptor well those passions read\r\n
Which yet survive, stamped on these lifeless things,\r\n
The hand that mocked them and the heart that fed.\r\n
And on the pedestal these words appear:\r\n
`My name is Ozymandias, King of Kings:\r\n
Look on my works, ye mighty, and despair!'\r\n
Nothing beside remains. Round the decay\r\n
Of that colossal wreck, boundless and bare,\r\n
The lone and level sands stretch far away\"


$c = new Content();
$c->author = "Percy Shelley";
$c->data = "$text";
$c->url = http://www.online-literature.com/shelley_percy/672/";
$c->title = "Ozymandias";
$c->add();
```

A few things are probably becoming apparent now. Objects are instatiated, their attributes set, and then they're either add()'d or update()'d. This is the base usage pattern for our objects. 

Objects can also be fetched via attributes, as you may have seen. Here's how we'd get all of Shelley's works in the system:

```s
$c = new Content();
$res = $c->getByKey("author", "Shelley");
```

We could also get all content containing a keyword. If we wanted to get content related to winter, we'd try something like this:

```s
$c = new Content();
$res = $c->getByKey("data", "winter);
```
Again, this is rudimentary. An ontology and automatic tagging are some of the improvements to come. 

###Event

We have the notion of capturing an event. Events in this rev of the model are system level user activity loggers. For example a logging that a user logged in, that user viewed a certain page, that they sent mail. Events are rather open ended though and can store anything you'd like. The basic properties of an event are:
- type: the type of event you're creating
- data: the stuff you want to store
- callback: a function that can be used to read the data for the event and do something interesting with it.

Here's a rather contrived example of an event in action, but it illustrates the idea.  

```s
$e = new Event();
$e->setType("log");
$data = array("user"=>$userid, 
    "activity"=>"sent mail", 
    "result"=>"fail",
    "reason"=>"mailbox full");
$e->setData($data);
$e->setCallback("mailEventsCalback");
$e->add();
```

License
----

MIT

*Free Software, Yes!*

  
    