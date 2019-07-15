<?php
session_start();
if (isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}
else
{
	header("Location: index.php");
    die();
}

$ex = $_REQUEST['errormessage'];
?>

<html>
<head>
    <title>
        Personnel List
    </title>
    <link href='https://fonts.googleapis.com/css?family=Gochi Hand' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Vibur' rel='stylesheet'>
    <link href="css/css.css" type="text/css" rel="stylesheet" />
    <link href="css/modal.css" type="text/css" rel="stylesheet" />
    <script src="js/Personnel.js"></script>
    <script src="js/modal.js"></script>
</head>
<body class="customStyle">
    <div class="container masthead">
        <div class="logo">
            
        </div>
        <div class="searchbar"></div>
    </div>
    <div id="PersonnelCon" class="container">

    <div>
    <div class="inline header customStyle">HEE-H-EML Team</div>
	<div class="inline right">
		<label for="ddlStyle" class="customStyle">Change Style:</label>
		<select id="ddlStyle">
			<option value="none">Standard</option>
			<option value="styleChalk">Chalk</option>
			<option value="styleNeon">Neon</option>
		</select>
	</div>
	</div>
        <br />
        <div id="divTable">
            <table id="Personnel" class="customStyle" border="1" cellpadding="1" cellspacing="0">
                <thead>
                    <tr>
                        <th class="customStyle invisibleColumn">Login_Id</th>    
                        <th class="customStyle">Name</th>
                        <th class="customStyle">Last Name</th>
                        <th class="customStyle">Out / In</th>
                        <th class="customStyle">location_Id</th>
                        <th class="customStyle">Location</th>
                        <th class="customStyle">Other</th>
                        <th class="customStyle">Return</th>
                        <th class="customStyle">Details</th>
                    </tr>
                </thead>
                <tbody id="tbody"></tbody>
            </table>
        </div>
        <div class="modal-azr" id="modalPerson">
            <div class="modal-azr-content">
                <span class="close-modal">&times;</span>
                <h1 id="modalName"></h1>
                <input type="hidden" id="modalLogin_Id" name="modalLogin_Id" />
                <input type="hidden" id="modalLocation_Id" name="modalLocation_Id" />
                <input type="hidden" id="modalStatus" name="modalStatus" />
                <label for="modalLocation">Location:</label>
                <select name="modalLocation" id="modalLocation" class="required"></select>
                <br />
                <div id="modalOtherDiv" class="inputOther hide">
                    <label for="modalOther">Location Other:</label>
                    <input name="modalOther" id="modalOther" type="text" />
                </div>
                <br />
                <label for="modalReturnTime">Return Time:</label>
                <input type="time" name="modalReturnTime" id="modalReturnTime" />
                <br />
                <label for="modalDetails">Details:</label>
                <input type="text" name="modalDetails" class="textarea-edit" id="modalDetails" />
                <br />
                <br />
                <button type="button" class="left" id="modalClose">Close</button>
                <button type="button" class="right" id="modalSave">Save</button>
                <br />
                </div>
        </div>
    </div>
</body>
</html>
