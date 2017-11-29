<html>
<head>
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://bootswatch.com/4/materia/bootstrap.min.css">

    <style>
        /**
 * Image slider with pure CSS
 * Original version in http://demosthenes.info/blog/css
 */
        .image-slider {
            position:relative;
            display: inline-block;
            line-height: 0;
        }

        /* Could use a pseudo-element, but they’re currently
           super buggy. See: http://dabblet.com/gist/ab432c3f6a8f672cd077 */
        .image-slider > div {
            position: absolute;
            top: 0; bottom: 0; left: 0;
            width: 25px;
            max-width: 100%;
            overflow: hidden;
            resize: horizontal;
        }

        /* Cross-browser resizer styling */
        .image-slider > div:before {
            content: '';
            position: absolute;
            right: 0; bottom: 0;
            width: 13px; height: 13px;
            padding: 5px;
            background: linear-gradient(-45deg, white 50%, transparent 0);
            background-clip: content-box;
            cursor: ew-resize;
            -webkit-filter: drop-shadow(0 0 2px black);
            filter: drop-shadow(0 0 2px black);
        }

        .image-slider img {
            user-select: none;
            max-width: 413px;
        }

        i {
            cursor: pointer;
        }
    </style>

</head>

<body>

<div id="app" class="container">




    <div id="accordion">
        <div class="card m-3" v-for="(page, key, index) in pages">
            <div class="card-header" >
                <div class="row">
                    <h6 class="col-4">
                        <a data-toggle="collapse" :href="'#' + page.title + index">
                            @{{ page.title | ucfirst }}
                        </a>
                    </h6>
                    <div class="col-4 text-center">

                         <span v-for="(value, dimension, index) in page.resolutions">

                                <i class="fa fa-2x fa-desktop"
                                   @click="page.selected = dimension"
                                   v-bind:class="{
                                   'text-primary': (page.selected == dimension),
                                   'fa-desktop' : (index == 0),
                                   'fa-tablet': (index == 1),
                                   'fa-mobile': (index == 2)
                                   }" aria-hidden="true"></i>
                                &nbsp; &nbsp; &nbsp;
                        </span>


                    </div>
                    <div class="col-4 text-right">
                        @{{ page.resolutions[page.selected].percentage }}% diff
                    </div>
                </div>
            </div>

            <div :id="page.title + index" class="collapse show">
                <div class="card-body">

                    <div class="row">
                        <div class="col">

                            <div class="image-slider">
                                <div>
                                    <a :data-fancybox="page.title + index" :href="page.resolutions[page.selected].before" data-caption="Before">
                                        <img :src="page.resolutions[page.selected].before" alt="before" />
                                    </a>
                                </div>
                                <a :data-fancybox="page.title + index" :href="page.resolutions[page.selected].after" data-caption="After">
                                    <img :src="page.resolutions[page.selected].after" alt="after"/>
                                </a>
                            </div>
                        </div>

                        <div class="col">
                            <a :data-fancybox="page.title + index" :href="page.resolutions[page.selected].difference" data-caption="Difference">
                                <img :src="page.resolutions[page.selected].difference" alt="diff" class="img-fluid">
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>



</div>










<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.13.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>

<script>
console.log( {!! $difference !!} );
    var app = new Vue({
        el: '#app',

        data: {
            pages:  {!! $difference !!}

    },
    filters: {
        ucfirst: function (value) {
            if (!value) return '';
            value = value.toString();
            return value.charAt(0).toUpperCase() + value.slice(1)
        }
    }
    })
</script>
</body>
</html>