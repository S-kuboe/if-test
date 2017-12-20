<!DOCTYPE html>
<html>
    <head>
        <title>Ziggeo PHP SDK and multiple video file uploading</title>
        <!-- CSS to have the sample look neat so it is not needed for this to work -->
        <style type="text/css">
            html, body {
                margin: 0;
                padding: 0;
                width: 100%;
                font-family: Arial;
                font-size: 14px;
            }
            form {
                display: block;
                margin: auto;
                width: 500px;
            }
            label {
                display: block;
                margin-top: 2em;
            }
            input, textarea {
                border: 1px solid lightblue;
                border-radius: 6px;
                font-size: 16px;
                padding: 4px;
            }
            input:focus, textarea:focus {
                box-shadow: 0 0 8px lightblue;
            }
            button {
                background-image: linear-gradient(lightBlue, blue);
                border: 3px outset lightgray;
                border-radius: 20px;
                color: white;
                display: block;
                font-weight: bold;
                height: 40px;
                margin: 40px;
            }
            button:hover {
                background-image: linear-gradient(blue, lightBlue);
                border-style: inset;
                color: lightblue;
            }
        </style>
    </head>
    <body>
        <!-- this could be used to push single files to server with Ziggeo PHP SDK or to set up flash to push multiple files instead with just few slight changes.. -->
		<?php if(!$blnDsp){ ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <input name="submitted" type="hidden" value="submitted">
            <p>This is a basic demo on using the power of Ziggeo SDK to upload multiple videos to Ziggeo in bulk</p>
            <label for="files">Choose the video files that you would like to upload to your server..</label>
            <input name="files[]" type="file" multiple>

            <label for="tags">Tags allow you to filter queries by tags. Specify them as comma-separated list.</label>
            <input name="tags" id="tags" type="text" placeholder="(Optional)">

            <button>Submit videos</button>
        </form>
		<?php }else{ ?>
			<p>Video files have been uploaded to your Ziggeo account</p>
		<?php } ?>
    </body>
</html>
