affiliates-for-all-fork
=======================

Magento Affiliates Plugin - Fork of Affiliates for All

OVERVIEW

This project is a fork of the popular Magento plug-in, Affiliates for All 
(http://www.affiliatesforall.org/)

I created this project in 2007 to augment an e-commerce site that I was acting 
lead developer on. I haven't been following the affiliates for all project for 
several years, but at the time, the software lacked some pivotal features that I
 needed. I had always intended to publish the code, but never managed to get 
around to it. It's been a while since I've looked at this code, but I'll make my
 best effort to summary the changes.

Summary of improvements
Upgraded codebase to JQuery-UI 1.7.2
Upgraded to JQuery 1.4.2
Added improved password encyption (passwords were being stored in tables as plaintext)
Implemented lost password recovery feature
Modified database schema
Created a two-tier affiliate system, where parent affiliates are able to 
participate in revenue sharing provided by child affiliate referals
Augmented session security mechanisms
Added commands to the xml-rpc interface
