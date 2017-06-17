<!--
/*
 * DON'T DELETE OR MOVE THIS FILE UNLESS YOU KNOW WHAT YOU ARE DOING.
 * YOU CAN EDIT THIS FILE AS LONG AS YOU USE THE FOLLOWING VARIABLES.
 * - {Error_Code}
 * - {Error_Message}
 */-->
<html>
<head>
    <style>
        @import url('//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

        .isa_error {
            margin: 10px 0;
            padding:12px;

        }

        .isa_error {
            color: #D8000C;
            background-color: #FFBABA;
        }

        .isa_error i {
            margin:10px 22px;
            font-size:2em;
            vertical-align:middle;
        }

        hr {
            border: 1px solid white;
        }
    </style>
</head>
<body>
<div class="isa_error">
    <i class="fa fa-times-circle"></i>
    PHP Version is not supported. You are running <b><?= phpversion(); ?></b>, while the framework needs <b>7.1</b> or
    higher!
</div>
</body>
</html>
