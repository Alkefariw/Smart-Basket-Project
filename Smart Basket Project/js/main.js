

function sign_up_form(event){
	event.preventDefault();
	
	let username = $("#username2").val().trim();
    let password = $("#password2").val().trim();
    let email = $("#email2").val().trim();
    let uid = $("#uid2").val().trim();

	let sbutton = $("#sbutton2").html(); //grab the initial content
	$(".form_error").remove();
    $("#sbutton2").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting form...');

    let fdata = {ch: 'sign_up', username, uid, email, password};
   $.ajax({
	 type: "POST",
	 url:   "",
	 data: fdata,
	 success: function(data){	
        if(data == 'PASS'){
            $("#cform2").html('<div style="font-size:17px" class="alert alert-success text-center py-5"><span class="fa fa-check-circle fa-5x text-success"></span> <br>Account created  successfully</div>');
        }
        else{
            $("#sbutton2").html(sbutton);
            try{
                let rdata = JSON.parse(data);
                rdata.forEach(function(row){
                    $("#"+row[0]).after('<div class="form_error">'+row[1]+'</div>')
                });
            }catch(exception){
                myalert(data);
            }
            
        }
     }
    });	
}




function sign_in_form(event){
	event.preventDefault();
	
	let username = $("#username").val().trim();
    let password = $("#password").val().trim();
    
	let sbutton = $("#sbutton").html(); //grab the initial content
	$(".form_error").remove();
    $("#sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Please wait...');

    let fdata = {ch: 'sign_in', username, password};
   $.ajax({
	 type: "POST",
	 url:   "",
	 data: fdata,
	 success: function(data){	
        if(data.substr(0,4) == 'PASS'){
            $("#cform").html('<div style="font-size:18px" class="alert alert-success text-center py-5"><span class="fa fa-check-o text-success"></span> <br> <h2>Loged in successfully.</h2> Redirecting to your account.. </div>');
            window.setTimeout(function(){ window.location.href = './'; }, 1500)
        }
        else{
            $("#sbutton").html(sbutton);
            try{
                let rdata = JSON.parse(data);
                rdata.forEach(function(row){
                    $("#"+row[0]).after('<div class="form_error">'+row[1]+'</div>')
                });
            }catch(exception){
                alert(data);
            }
            
        }
     }
    });
	
}


function myalert(msg){

    $("#modal_alert .modal-body").html(msg);
    $("#modal_alert").modal('show');

}



function timeAlert(type, message){

    $("#notifyType").html(message);
    if(type == 'success'){
        $(".notify").toggleClass("active");
        $("#notifyType").toggleClass("success"); 
        setTimeout(function(){
        $(".notify").removeClass("active");
        $("#notifyType").removeClass("success");
        },2000);
    }
    else{
        $(".notify").addClass("active");
        $("#notifyType").addClass("failure");   
        setTimeout(function(){
        $(".notify").removeClass("active");
        $("#notifyType").removeClass("failure");
        },2000);
  }

}

//myConfirm();

function myConfirm(type, title, message, action){

    new DNDAlert({
        theme: 'dark',
        type: type,
        draggable: true,
        title: title,
        message: message,
        buttons: [
            {
                text: "Confirm",
                type: 'primary',
                click: (bag) => {
                    bag.CLOSE_MODAL();
                    eval(action);
                },
            },
            {
            text: "Cancel",
            click: (bag) => {
                bag.CLOSE_MODAL();
            },
            },
        ]
    });

}



function myAlert(type, message){
    new DNDAlert({
        theme: 'white',
        type: type,
        html: true,
        draggable: true,
        message: message,
        buttons: []
    });
    setTimeout(function(){
        $(".dnd-alert-container").remove();
    }, 3000);

}



const switchers = [...document.querySelectorAll('.switcher')]

switchers.forEach(item => {
	item.addEventListener('click', function() {
		switchers.forEach(item => item.parentElement.classList.remove('is-active'))
		this.parentElement.classList.add('is-active')
	})
})
