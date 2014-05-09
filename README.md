PasswordX
=========

A web accessible password manager that doesn't suck

* Gives you control over how your passwords are stored, formatted and presented

* Easy to search for the stuff you need

* Built on a CMS core so that you can add any type of content on your pages side-by-side with your passwords, such as file attachments, html content, youtube videos, maps, and well ... Any of the myriad of blocks found in the Concrete5 marketplace

* Time-saving conveniences such as hover and copy, auto-linking of URL's and more

* Easy to install, drop the files on the server, visit the site and follow the on screen installer

```
PasswordX is designed for a LAMP stack but works on Windows webservers running IIS 
and PHP as well. Check out our System Requirements page for more detailed information.
```

* Optional long lived sessions mean that you don't have to log in to the damned thing every day on your work machine

* Responsive theme that just works on the latest iOS and Android devices

* Easy to customize and brand just the way you like it

* Tries to be helpful in setting up two-factor authentication, so you can keep using your tired old crappy passwords

* Tries to be helpful in encouraging you to set up SSL

## Installation Instructions

1. Create a new MySQL database and a MySQL user account with the following privileges on that database: INSERT, SELECT, UPDATE, DELETE, CREATE, DROP, ALTER
1. Visit your site in your web browser. You should see an installation screen where you can specify your settings.
1. PasswordX 5 should be installed.

# PasswordX and security

Our #1 concern is security. We believe we have designed a system that is as secure as can reasonably be expected, given the myriad of attack vectors beyond our control (compromised clients, government surveillance  with crazy NSA super spy gear, social engineering etc.). 


```
We hope that our peers will contribute with their attention and time 
and that they will try to break the system to our mutual benefit.
```

We rely in part on the integrity of the core system, Concrete5. Concrete5 has been actively open sourced for more than 6 years and has fairly well vetted security mechanisms. We have tried to harden the core wherever we knew there might be potential risks, and have taken care to try and design a solid way of handling encryption. [[Read more about our approach here|https://github.com/KramericaIndustries/PasswordX/wiki/Passwords-&-Encryption]]


```
There are almost certainly ways to compromise the system. 
Please help us by reviewing our authentication flow, and help us think outside the box!
```

Our focus will first and foremost be on our own code and password security, but if we stumble on weak links in the core system we will contribute these back to the main Concrete5 repository.

In addition, we call on our peers to help us author guides on how to set up a webserver for PasswordX in as responsible a manner as possible.

```
PasswordX is built to help regular people do their job. If you are targeted by 
the full force of a government sponsored spy agency, this software is not for you.
```
