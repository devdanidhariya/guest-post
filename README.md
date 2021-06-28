# Guest Post 

Contributors: devdanidhariya
Tags: Guest Post, CTP, form,
Requires PHP: 5.6
Tested up to: 5.1.10
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


This exercise is about creating an interface in Front-end site of website, so that guest authors can submit posts from front-side.


## Description

Using this plug-in user can create beautiful guest post submit form. 

This exercise is about creating an interface in Front-end site of website, so that guest authors
can submit posts from front-side. Using this interface, the guest author should be able to create
a post from front side. You will also need to create another page where all the posts created by
this author will be listed.

Guest Post is the most powerful and intuitive WordPress plug-in to create custom post type form builder. Fully responsive and works with any WordPress theme. Create beautiful post and tell stories without any code.

## Demo
- Demo URL: https://multidots.devidas.in/
- Add guest post page: https://multidots.devidas.in/add-post/
- List guest post page: https://multidots.devidas.in/list-guest-posts/

## Multidots review team

### login credentials
##### Administrator user
- Login URL: https://multidots.devidas.in/wp-admin
- User name: multidots
- Password: 4#R#c#unHaYvtQpBKVqLiN2)

##### Author role user
- Login URL: https://multidots.devidas.in/wp-admin
- User name: md_author
- Password:  KMFhsMaQ^la*f$(DykksA@XS

## Installation

1. Upload the `guest-post` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

## Shortcode

1. Add Guest post form shortcode `[gp_add_post /]` 
2. Show the list of posts which are in pending status for admin approval shortcode `[gp_list_posts /]` 

## Usage Examples 

 After active plugin, you should create a new user from wp-admin dashboard with **Author role** or also you can use exist **author role** user's.

1. If user with <b>author role</b> not exist then go to wp-admin dashboard and create new user with author role.
2. For add custom post type form go to wp-admin `dashboard` &#8594; `page` &#8594; `new page` or use existing page and add `[gp_add_post /]` in post editor or  gutenberg shortcode block. 
4. Show the list of posts which are in pending status for admin approval showing go to wp-admin `dashboard` &#8594; `page` &#8594; `new page` or use existing page and add `[gp_list_post /]` in post editor or  Gutenberg shortcode block.

## Features

* Create responsive custom post type form with save post data in custom post type functionality. 
* Provision to easily add a guest post form to any post or page via a short code.
* Using shortcode to show the list of posts which are in pending status for admin approve.
* Anywhere usable shortcode in post and page and a custom PHP file.
* After add new post admin can get notification mail.
* show pending status post using shortcode.
* Much more...

## WP-CLI

##### The default guest post plugin role is author. But you can change this role using wp-cli.


###### Using this command to you can change default guest post role.
```sh
$ wp guest_post set_gp_author --role=administrator
```
###### Using this command to you can check current  guest post role
```sh
$ wp guest_post get_gp_author
```



## Frequently Asked Questions


## Screenshots


* ### Add guest post form
![GitHub Logo](http://multidots.devidas.in/wp-content/uploads/2021/06/guest-post-add-post-form.png)

* ### Pending status for admin approval post list
![GitHub Logo](http://multidots.devidas.in/wp-content/uploads/2021/06/guest-posts-review-post-list.png)

* ### WP-CLI screen
![GitHub Logo](http://multidots.devidas.in/wp-content/uploads/2021/06/wp-cli.png)


## Change log


## 1.0.0
* First release