# The Idea
The idea/project is based on [http://arguseyes.io/](), a tool used for visual image regression on your local dev with a nice output.
   Since the project is not maintaned anymore, the code contains bugs and ran too slow and only on one browser I figured I wanted to recreate this. With the power of Laravel, Dusk and Imagick I managed to recreate the project and even better.

# Usage
Let's say we're running a project in production at tag 4.2.0.
To capture the current state of the project we run
`php artisan image:capture 4.2.0`

This command will read the eyes.json file, run all URLs against all the sizes and make a screenshot, it will store the files at storage/app/.eyes/4.2.0

Assume we're 2 months later, we're almost done with developing tag 4.3.0, to see that we did not break any visual elements we run the following command
`php artisan image:capture 4.3.0` (this will again capture everything and store the screenshots).

Now we have 2 folders full of screenshots from 2 different versions, let's compare them to see the visual difference
`php artisan image:compare 4.2.0 4.3.0` 

You can now navigate to `storage/app/.eyes/diff_4.2.0_4.3.0` and open the `output.html` to see the differences.

