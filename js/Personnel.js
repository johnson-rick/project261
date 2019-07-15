document.addEventListener("DOMContentLoaded", function (event) {
    const modal1 = new Modal("modalPerson");
    const modalLogin_Id = document.getElementById('modalLogin_Id');
    const modalName = document.getElementById('modalName');
    const modalStatus = document.getElementById('modalStatus');
    const modalLocation = document.getElementById("modalLocation");
    const modalOtherDiv = document.getElementById("modalOtherDiv");
    const modalOther = document.getElementById("modalOther");
    const modalClose = document.getElementById("modalClose");
    const modalReturnTime = document.getElementById("modalReturnTime");
    const modalDetails = document.getElementById("modalDetails");
    const ddlStyle = document.getElementById('ddlStyle');

    var style = getItemFromStorage('Style');

    class Person {
        constructor(login_id, full_name, status, location_id, other, _return, details) {
            this.login_id = login_id;
            this.full_name = full_name;
            this.status = status;
            this.location_id = location_id;
            this.other = other;
            this._return = _return;
            this.details = details;
        }
    }

    function getPersonnel() {

        var function2call = { 'function2call': 'get_personnel' };
        var data = JSON.stringify(function2call);

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var results = JSON.parse(xhr.responseText);
                callback(results);
            }
        }
        xhr.open("POST", "ProcessAjax.php");
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
        xhr.send(data);
    }

    function callback(results) {
        //var count = results.totalItems;
        CreateTable(results);
        addListeners();

        if (style != null || style != undefined) {
            changeStyle(style);
            ddlStyle.value = style;
        }
    }

    function getLocations() {

        var function2call = { 'function2call': 'get_locations' };
        var data = JSON.stringify(function2call);

        var xhr2 = new XMLHttpRequest();
        xhr2.onreadystatechange = function () {
            if (xhr2.readyState === 4 && xhr2.status === 200) {
                var results = JSON.parse(xhr2.responseText);
                callback2(results);
            }
        }
        xhr2.open("POST", "ProcessAjax.php");
        xhr2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
        xhr2.send(data);
    }

    function callback2(results) {
        locations = results;
    }

    function SavePerson(person) {

        var function2call = { 'function2call': 'update_person', person };
        var data = JSON.stringify(function2call);

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var results = JSON.parse(xhr.responseText);
                callback3(results);
            }
        }
        xhr.open("POST", "ProcessAjax.php");
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
        xhr.send(data);
    }

    function callback3(results) {
        if (results > 0)
        {
            modal1.hide();
        }
    }

    getPersonnel();
    getLocations();

    function CreateTable(personnel) {
        var dataitems = [];
        personnel.forEach(function (item) {
            var data = [];
            data.push(item.Login_id,
                item.First_Name,
                item.Last_Name,
                item.Status,
                item.Location_Id,
                item.Location,
                item.Location_Other,
                getValue(item.Return_Time),
                item.Details);
            dataitems.push(data);
        });

        var table = document.getElementById('Personnel').getElementsByTagName('tbody')[0];
        if (dataitems.length > 0) {
            var divTable = document.getElementById('divTable');
            divTable.className = "visible";
        }

        for (var i = 0; i < dataitems.length; i++) {
            //create a new row
            var newRow = table.insertRow(table.rows.length);
            newRow.id = dataitems[i][0];
            newRow.first_name = dataitems[i][1];
            newRow.last_name = dataitems[i][2];
            newRow.status = dataitems[i][3];
            newRow.location_Id = dataitems[i][4];
            newRow.location = dataitems[i][5];
            newRow.other = dataitems[i][6];
            newRow._return = dataitems[i][7];
            newRow.details = dataitems[i][8];
            newRow.className = "customStyle"

            for (var j = 0; j < dataitems[i].length; j++) {
                //create a new cell
                var cell = newRow.insertCell(j);
                cell.className = 'customStyle';
                //add value to the cell
                if (j == 3) {
                    if (dataitems[i][j] == true) {
                        cell.innerHTML = "<label class='switch'><input type='checkbox' class='check' id='check" + i + "' checked><span class='customStyle slider round'></span></label>";
                    }
                    else {
                        cell.innerHTML = "<label class='switch'><input type='checkbox' class='check' id='check" + i + "'><span class='customStyle slider round'></span></label>";
                    }

                }
                else {
                    cell.innerHTML = dataitems[i][j];
                }
                cell.className = " center customStyle";

                if (j == 0) {
                    cell.className = "invisibleColumn";
                }
            }
        }
    }

    function getValue(itemValue) {
        if (itemValue != null && itemValue != undefined) {
            return itemValue;
        }
        else {
            return "";
        }
    }

    function addListeners() {
        var icons = document.getElementsByClassName("check");
        //console.log(icons);
        //console.log('array length: ' + icons.length);
        for (var i = 0; i < icons.length; i++) {
            console.log(icons[i]);
            icons[i].addEventListener(
                'change',
                function () {
                    var self = this;
                    //console.log(self.checked);
                    var row = self.parentNode.parentNode.parentNode;
                    var rowId = row.id;
                    //var cell = row.cells.item(5).innerHTML;
                    //console.log(cell);
                    var fullName = row.first_name + ' ' + row.last_name;
                    var status = self.checked;
                    var location_Id = row.location_Id;
                    var other = row.other;
                    var _return = row._return;
                    var details = row.details;
                    let person = new Person(parseInt(rowId), fullName, status, location_Id, other, _return, details);
                    setLocation(locations);

                    if (self.checked == false) {
                        loadModal(person, row);
                        row.cells.item(1).classList.add('Out');
                    }
                    else {
                        //column updates
                        row.cells.item(1).classList.remove('Out');
                        row.cells.item(4).innerHTML = '1'; // location_Id
                        row.cells.item(5).innerHTML = 'In the Office'; // location
                        row.cells.item(6).innerHTML = ''; // Other
                        row.cells.item(7).innerHTML = ''; // Return
                        row.cells.item(8).innerHTML = ''; // Details

                        person.location_id = 1;
                        person.other = '';
                        person.details = '';
                        person._return = '';
                        person.status = true;
                        SavePerson(person);
                    }
                },
                false
            )
        };
    }

    function loadModal(person, row) {
        modalLogin_Id.value = person.login_id;
        modalStatus.value = person.status;
        modalName.innerHTML = person.full_name;
        modalLocation.value = person.location_id;
        modalOther.value = person.other;
        modalReturnTime.value = person._return
        modalDetails.value = person.details;

        if (modalLocation.value == 13) {
            modalOtherDiv.classList.remove('hide', 'inline');
            modalOtherDiv.classList.add('unhide', 'inline');
        }

        modalLocation.addEventListener(
            'change',
            function () {
                if (this.value == 13) // Other
                {
                    modalOtherDiv.classList.remove('hide', 'inline');
                    modalOtherDiv.classList.add('unhide', 'inline');
                }
                else {
                    modalOtherDiv.classList.remove('unhide', 'inline');
                    modalOtherDiv.classList.add('hide', 'inline');
                    modalOther.innerHTML = '';
                    modalOther.value = '';
                }
            },
            false
        );
        modalClose.addEventListener(
            'click',
            function () {
                modal1.hide();
            },
            false
        );

        modalSave.addEventListener(
            'click',
            function () {
                //Save record
                var loginId = modalLogin_Id.value;
                var name = modalName.value;
                var status = modalStatus.value;
                var locationId = modalLocation.options[modalLocation.selectedIndex].value;
                var location = modalLocation.options[modalLocation.selectedIndex].text;
                var other = modalOther.value;
                var _return = modalReturnTime.value;
                var details = modalDetails.value;

                let person = new Person(loginId, name, status, locationId, other, _return, details);
                SavePerson(person);

                //column updates
                row.cells.item(4).innerHTML = locationId.toString();
                row.cells.item(5).innerHTML = location.toString();
                row.cells.item(6).innerHTML = other.toString();
                row.cells.item(7).innerHTML = _return.toString();
                row.cells.item(8).innerHTML = details.toString();
                //clear row
                row = null;
            },
            false
        );
        modal1.show();
        setFocus(modalLocation);
    }

    function setLocation(locations) {
        var select = document.getElementById("modalLocation");

        // Optional: Clear all existing options first:
        select.innerHTML = "";
        // Populate list with options:
        for (var i = 0; i < locations.length; i++) {
            var opt = locations[i];
            var el = document.createElement("option");
            el.textContent = opt.Location;
            el.value = opt.Location_Id;
            select.appendChild(el);
        }
    }

    function setFocus(ctrl) {
        ctrl.focus();
    }

    document.getElementById('ddlStyle').addEventListener(
        'change',
        function () {
            changeStyle(this.value);
            localStorage.removeItem("Style");
            setItemInStorage("Style", this.value)
        },
        false
    );

    function changeStyle(style) {
        switch (style) {
            case 'styleChalk':
                var rows = document.getElementsByClassName("customStyle"),
                    len = rows !== null ? rows.length : 0,
                    i = 0;
                for (i; i < len; i++) {
                    rows[i].classList.add('styleChalk');
                    rows[i].classList.remove('styleNeon');
                }
                break;
            case 'styleNeon':
                var rows = document.getElementsByClassName("customStyle"),
                    len = rows !== null ? rows.length : 0,
                    i = 0;
                for (i; i < len; i++) {
                    rows[i].classList.remove('styleChalk');
                    rows[i].classList.add('styleNeon');
                }
                break;
            default:
                var rows = document.getElementsByClassName("customStyle"),
                    len = rows !== null ? rows.length : 0,
                    i = 0;
                for (i; i < len; i++) {
                    rows[i].classList.remove('styleChalk');
                    rows[i].classList.remove('styleNeon');
                }
        }
    }

    setInterval(function () {
        window.location.reload();
    }, 300000);

    function setItemInStorage(dataKey, data) {
        localStorage.setItem(dataKey, JSON.stringify(data));
    }

    function getItemFromStorage(dataKey) {
        var data = localStorage.getItem(dataKey);
        return data ? JSON.parse(data) : null;
    }

});



