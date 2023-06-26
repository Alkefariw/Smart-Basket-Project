let  storedISBNs = [];

let html = '<img src="cid:attachment">';


function searchISBN() {
    //console.log(storedISBNs);
    // Get the ISBN input by the user
    var ISBN = document.getElementById("ISBN").value.trim();

    if(storedISBNs.includes(ISBN)){
        $("#bookTableBody tr").each(function(){
            if($(this).find('td:eq(0)').html().trim().toLowerCase() == ISBN.trim().toLowerCase()) {
                $(this).remove();
                storedISBNs.splice(storedISBNs.indexOf(ISBN),1);
            }
        });
        timeAlert("error", "This ISBN already exists in the table, it's been removed");
        document.getElementById("ISBN").value = "";
        resetButtons();
        return;
    }
    
    // Use fetch() method to retrieve book information from OpenLibrary
    fetch('https://openlibrary.org/api/books?bibkeys=ISBN:' + ISBN + '&format=json&jscmd=data')
        .then(response => response.json())
        .then(data => {
            // Check if ISBN is valid
            if(Object.keys(data).length === 0) {
                timeAlert("error","Invalid ISBN");
                document.getElementById("ISBN").value = "";
                return;
            }
            if(storedISBNs.includes(ISBN)){
                $("#bookTableBody tr").each(function(){
                    if($(this).find('td:eq(0)').html().trim().toLowerCase() == ISBN.trim().toLowerCase()) {
                        $(this).remove();
                        storedISBNs.splice(storedISBNs.indexOf(ISBN),1);
                    }
                });
                timeAlert("error", "This ISBN already exists in the table, it's been removed");
                document.getElementById("ISBN").value = "";
                resetButtons();
                return;
            }
            storedISBNs.push(ISBN);
            resetButtons();
            // Extract the book information from the JSON data
            var title = data['ISBN:' + ISBN].title;
            var author = data['ISBN:' + ISBN].authors[0].name;
            var publisher = data['ISBN:' + ISBN].publishers[0].name;
            var publicationDate = data['ISBN:' + ISBN].publish_date;
            // Create a new table row with the book information
            var table = document.getElementById("bookTableBody");
            var row = table.insertRow();
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);
            var cell6 = row.insertCell(5);
            cell1.innerHTML = ISBN;
            cell2.innerHTML = title;
            cell3.innerHTML = author;
            cell4.innerHTML = publisher;
            cell5.innerHTML = publicationDate;
            var input = document.createElement("input");
            input.setAttribute("class", "datepicker form-control");
            input.setAttribute("type", "text");
            input.setAttribute("placeholder", "Choose return date");
            cell6.appendChild(input);
            $('.datepicker').datepicker();
        })
        .catch(error => console.error(error));
    document.getElementById("ISBN").value = "";
}
document.getElementById("ISBN").addEventListener("keyup", function(event) {
    event.preventDefault();
    if (event.keyCode === 13) {
        searchISBN();
    }
});

document.getElementById("clearBtn").addEventListener("click", function() {
    clearEntries(0);
});

document.getElementById("logOut").addEventListener("click", function() {
    logOut(0);
});

document.getElementById("sendBtn").addEventListener("click", function(event) {
    sendEmail(event, 0)
});


document.getElementById("addBookBtn").addEventListener("click", function() {
    $("#modalAddBook form")[0].reset();
    $("#modalAddBook").modal('show');
});

resetButtons();
$('#rDate').datepicker();

function addBook(event){

    event.preventDefault();

    // Extract the book information from the Form
    let title = document.getElementById('title').value.trim();
    let ISBN = document.getElementById('formISBN').value.trim();
    let author = document.getElementById('author').value.trim();
    let publisher = document.getElementById('publisher').value.trim();
    let publicationDate = document.getElementById('pDate').value.trim();
    let returnDate = document.getElementById('rDate').value.trim();

    if(storedISBNs.includes(ISBN)){
        timeAlert("error", "This ISBN already exists in the table");
        return;
    }

    storedISBNs.push(ISBN);
    resetButtons();

    // Create a new table row with the book information
    var table = document.getElementById("bookTableBody");
    var row = table.insertRow();
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    var cell4 = row.insertCell(3);
    var cell5 = row.insertCell(4);
    var cell6 = row.insertCell(5);
    cell1.innerHTML = ISBN;
    cell2.innerHTML = title;
    cell3.innerHTML = author;
    cell4.innerHTML = publisher;
    cell5.innerHTML = publicationDate;
    var input = document.createElement("input");
    input.setAttribute("class", "datepicker form-control");
    input.setAttribute("type", "text");
    input.setAttribute("placeholder", "Choose return date");
    input.value = returnDate;
    cell6.appendChild(input);
    $('.datepicker').datepicker();
    $("#modalAddBook").modal('hide');
}


function clearEntries(ch){
    if(ch == 0){
        myConfirm('warning', 'Confirm', 'Are you sure to remove all your books in your table? To remove a book just scan it again.', 'clearEntries(1)'); 
        return;
    }
    else{
        document.getElementById("bookTableBody").innerHTML = "";
        storedISBNs = [];
        resetButtons();
    }


}


function logOut(ch){
    if(ch == 0){
        myConfirm('warning', 'Confirm you want to logout', 'All your changes will be delete it and will take you to log page', 'logOut(1)');
        return;
    }
    else{
        window.location.href = "logout";
    }
}


function sendEmail(event, ch){
    if(ch == 0){
        myConfirm('info', 'Confirm your request', 'Confirm you want to end to Email', 'sendEmail(\'\', 1)');
        return;
    }
    $("#sendBtn").html('Sending...').prop('disabled', true);

    html2canvas(document.querySelector(".main_page"), {
        scale: 1.0,
        useCORS: true,
        //logging: true,
        letterRendering: 1,
        allowTaint: true,
        backgroundColor: "#FFF",
        //removeContainer: true,
    }).then(async canvas => { 
        //document.body.appendChild(canvas);
        
       let imgData = canvas.toDataURL('image/png');
       let file = dataURLtoBlob(imgData);

       let fd = new FormData();
       fd.append('file', file);
       fd.append('html', html);
       
       
       fetch("./mail.php", {
        method: 'POST',
        body: fd,
        })
        .then(res => res.text())
        .then(data => {
            if (data == "PASS") {
                $("#sendBtn").html('Send to Email').prop('disabled', false);
                myAlert('success', '<div style="weight:bold; font-size:20px" class="text-success">Success</div><p>Email sent successfully!</p>');
            } else {
                $("#sendBtn").html('Send to Email').prop('disabled', false);
                timeAlert('error', 'FAILED...', data);
            }
        })
        .catch(err => {
            $("#sendBtn").html('Send to Email').prop('disabled', false);
            timeAlert('error', 'FAILED...', error.message);
        });
        
       
    
    });
    
}



function resetButtons(){
    if(storedISBNs.length == 0){
        document.getElementById("printBtn").style.display = 'none';
        document.getElementById("clearBtn").style.display = 'none';
        document.getElementById("sendBtn").style.display = 'none';
    }
    else{
        document.getElementById("printBtn").style.display = 'block';
        document.getElementById("clearBtn").style.display = 'block';
        document.getElementById("sendBtn").style.display = 'block';
    }

}



function dataURLtoBlob(dataURI) {
    // convert base64 to raw binary data held in a string
   
    var byteString = atob(dataURI.split(',')[1]);

    // separate out the mime component
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0]

    // write the bytes of the string to an ArrayBuffer
    var ab = new ArrayBuffer(byteString.length);

    // create a view into the buffer
    var ia = new Uint8Array(ab);

    // set the bytes of the buffer to the correct values
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    // write the ArrayBuffer to a blob, and you're done
    var blob = new Blob([ab], {
        type: mimeString
    });
    return blob;
}

(function(){
    emailjs.init("MIDpCcAO2dMehfJr_");
 })();
