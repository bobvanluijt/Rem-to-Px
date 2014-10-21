#Rem to Px

With this code, you can update any stylesheet that is based on REMs to be used in < IE9

Background: one of my clients wants to use Foundation 5.x but also on IE8. By extending the REMs to pixels, the problem was solved from a CSS pov.

## Table of contents
- [Usage](#Usage)
- [Info](#Info)
- [Credits](#Credits)

## Usage
You can use it in 2 ways:<br>
1) Download the .zip and install it on your server<br>
2) Directly send your CSS file to my server and have it rendered: http://kubrickolo.gy/rem_and_px<br>
Example:<br>
```http://kubrickolo.gy/rem_and_px/?u=https://someurl.com/style.css```

If you want to you can add the param `uval` this will skip the search for the font-size value used to determine REM

Example 1:<br>
```http://kubrickolo.gy/rem_and_px/?u=https://someurl.com/style.css```

Example 2:<br>
```http://kubrickolo.gy/rem_and_px/?u=https://someurl.com/style.css&uval=20%```

Example 3:<br>
```http://kubrickolo.gy/rem_and_px/?u=https://someurl.com/style.css&uval=20px```

## Info
Use it directly on: http://kubrickolo.gy/<br>
Contact: bob.vanluijt@elsevier.com

## Credits
Created by: Bob van Luijt (Kubrickology)<br>
Twitter: (https://twitter.com/bobvanluijt)
