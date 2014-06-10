---
layout: doc
title: Quickstart
---

# Burly Quickstart

* [Requirements](#requirements)
* [Installing Burly](#install-burly)
* [Creating your Theme](#create-theme)
* [Installing your Theme](#install-theme)
* [Creating a Page](#create-page)
* [Editing Content](#edit-content)

## Requirements {#requirements}

Burly assumes that your are capable of the following:

* Building a website using HTML & CSS
* Acquiring a [hosting plan](http://wordpress.org/hosting/) that allows WordPress installation
* Installing WordPress on your hosting plan (ask your hosting provider)
* Using FTP/SFTP to access to your WordPress hosting plan (ask your hosting provider)

## Installing Burly {#install-burly}

After installing WordPress on your hosting plan:

1. [Download](https://github.com/elbongurk/burly/releases) the Burly WordPress framework theme
2. In the WordPress admin, navigate to the *Apperance* menu screen
3. Select *Add New* from the top of the *Apperance* screen
4. Select the *Upload* option
5. Select *Choose File* and locate your `burly.zip` to upload
6. Select *Install Now*

## Creating your Theme {#create-theme}

You should have a folder that already contains all the HTML, CSS, Javascript and other assets that your website requires. 

As an example, we will assume you've created this folder and named it `my-theme`. To turn this folder into a WordPress theme you are required to have the following file.

### `style.css`

A file named `style.css` must exist directly inside your `my-theme` folder.  This `style.css` file must contain the following information at the beginning of the file:

{% highlight css %}
/*
Theme Name: My Theme
Template: burly
*/
{% endhighlight %}

## Installing your Theme {#install-theme}

To install your theme, create a zip file of your `my-theme` folder. Now install your theme just like you installed Burly:

1. In the WordPress admin, navigate to the *Apperance* menu option
2. Select *Add New* from the top of the *Apperance* screen
3. Select the *Upload* option
4. Select *Choose File* and locate your `my-theme.zip` to upload
5. Select *Install Now*
6. Select *Activate* to begin using your theme after its uploaded

## Creating a Page {#create-page}

Navigate to your homepage to check to see if your `index.html` file appears. By doing so you've just created your first WordPress page with Burly.

Burly will create WordPress pages for any `.html` file in your `my-theme` folder by you simply navigating to them in your browser just as you would normally.

As an example, if you had a file named `about.html` and navigated to `http://yoursite.com/about.html` in your browser, Burly will create a WordPress page for that file named *About*.

## Editing Content {#edit-content}

Now that you have created some WordPress pages through Burly, lets get to adding sections for our client to edit.

As an example, lets say you have an `index.html` file in your `my-theme` folder with the following contents:

{% highlight html %}
<!DOCTYPE html>
<html>
<head>
    <title>My Theme</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
   <h1>My Theme</h1>
   <p>This is a very basic amount of information about my theme.</p>
</body>
</html>
{% endhighlight %}

Now lets say your goal is to make the contents of that `<p>` tag available to edit from within WordPress. 

To do so you'll first need to find the `index.html` file to edit inside your FTP client of choice as it now resides on your WordPress hosting plan.  Once you have your FTP credentials you should be able to find the file using this path:

{% highlight bash %}
public_html/wp-content/themes/my-theme/index.html
{% endhighlight %}

Now that you've located this file, edit it to look like the following:

{% highlight html %}
<!DOCTYPE html>
<html>
<head>
    <title>My Theme</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
   <h1>My Theme</h1>
   <p>{%raw%}{{burlyText description}}{%endraw%}</p>
</body>
</html>
{% endhighlight %}

Navigate to your homepage and you'll now see that this text has disappeared! Don't panic! It's because this text is now become editable on the page. 

To check, go to your WordPress admin and select the *Pages* menu option and open the *Home* page. You should now see a spot to place your description text. Go ahead an place some text here and update the page and go back and you'll see your text is now being pulled from WordPress.
