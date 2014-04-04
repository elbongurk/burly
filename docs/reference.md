---
layout: doc
title: Reference
---

# Burly Reference

Burly tags are the small blocks of code inserted into the html file that indicate specific areas that need to be editable from the WordPress administrative panel.

Typically these tags are used to replace the content (text, image, link, etc) of any HTML tag, so that any style from the site's existing stylesheet will dictate the appearance of the content of the Burly tag, identically to the way it would if the content were typed directly between the html tags.

## burlyText {#burly-text}

![burlyText Preview](/assets/img/burlyText.jpg)

Indicates to WordPress that there is editable text. It will appear in the WordPress administrative panel as a single-line input box.

### Example

{% highlight html %}
<h1>{%raw%}{{burlyText heading}}{%endraw%}</h1>
{% endhighlight %}

## burlyTextArea {#burly-textarea}

![burlyTextArea Preview](/assets/img/burlyTextArea.jpg)

Indicates to WordPress that there is a large amount of editable text. It will appear in the WordPress administrative panel as a multi-line input box. Note that `<p>` tags will be automatically added for each new line of text entered into the mutli-line input box.

### Example

{% highlight html %}
<div class="my-desc">
    {%raw%}{{burlyTextArea description}}{%endraw%}
</div>
{% endhighlight %}

## burlyEditor {#burly-editor}

![burlyEditor Preview](/assets/img/burlyEditor.jpg)

Indicates to WordPress that there is a large amount of editable content. It will appear in the WordPress administrative panel as a multi-line input box will full featured markup and styling tools.

### Example

{% highlight html %}
<div class="my-desc">
    {%raw%}{{burlyEditor description}}{%endraw%}
</div>
{% endhighlight %}

## burlyImage {#burly-image}

![burlyImage Preview](/assets/img/burlyImage.jpg)

Indicates to WordPress that there will be an image. It will appear in the WordPress administrative panel as a image upload button. Client will be able to use images from the media library or upload new images from their computer.

### Example

{% highlight html %}
<figure class="hero">
    <img src="{%raw%}{{burlyImage hero}}{%endraw%}"/>
</figure>
{% endhighlight %}

## burlyFile {#burly-file}

![burlyFile Preview](/assets/img/burlyFile.jpg)

Indicates to WordPress that there will be a file that needs to be uploaded and displayed on the page. It will appear in the WordPress administrative panel as a add file button. Client will be able to use files from the media library or upload new files from their computer.

### Example

{% highlight html %}
<p>
  <a href="{%raw%}{{burlyFile file}}{%endraw%}">Download our Menu</a>
</p>
{% endhighlight %}

## burlyGroup {#burly-group}

![burlyGroup Preview](/assets/img/burlyGroup.jpg)

Indicates to WordPress that the Burly commands are a related set, and should be displayed together in a single editing panel in the administrative dashboard.

### Example

{% highlight html %}
<div class="my-people">
    {%raw%}{{#burlyGroup people}}{%endraw%}
        <p>{%raw%}{{burlyText first}}{%endraw%}</p>
        <p>{%raw%}{{burlyText last}}{%endraw%}</p>
        <figure>
            <img src="{%raw%}{{burlyImage photo}}{%endraw%}"/>
        </figure>
    {%raw%}{{/burlyGroup}}{%endraw%}
</div>
{% endhighlight %}

## burlyRepeater {#burly-repeater}

![burlyRepeater Preview](/assets/img/burlyRepeater.jpg)

The burlyRepeater is similar to the burlyGroup. It allows for separate Burly commands to be displayed together as a set in the administrative dashboard. However, it also creates the group as a repeatable module so the user can add additional sets of that grouped information from the administrative dashboard.

### Example

{% highlight html %}
<ul>
    {%raw%}{{#burlyRepeater ingredients}}{%endraw%}
        <li>{%raw%}{{burlyText name}}{%endraw%}</li>
    {%raw%}{{/burlyRepeater}}{%endraw%}
</ul>
{% endhighlight %}

## burlyTitle {#burly-title}

![burlyTitle Preview](/assets/img/burlyTitle.png)

 Inserts the WordPress page title into your HTML. Typically used in the `<title>` tag of the `<head>` section.

### Example

{% highlight html %}
<!DOCTYPE html>
<html>
<head>
  <title>{%raw%}{{burlyTitle}}{%endraw%}</title>
</head>
<body>
</body>
</html>
{% endhighlight %}
