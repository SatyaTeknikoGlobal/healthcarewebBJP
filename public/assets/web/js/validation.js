var site_path=$("#site_path").val();$.validator.addMethod("mvalid",function(value,element)
{var num=$("#mobile").val().substr(0,1);var mlength=$("#mobile").val().length;if($("#mobile").hasClass("mvalid")==true&&(num!=7)&&(num!=8)&&(num!=9))
{$.validator.messages.mvalid='Mobile Number should be start with 9 , 8 or 7 ';return false;}
else if($("#mobile").hasClass("mvalid")==true&&(mlength!=10))
{$.validator.messages.mvalid='Enter correct 10 Digit Mobile No.';return false;}
return true;});$.validator.addMethod("emailormobile",function(val,el,param){var valid=false;for(var i=0;i<param.length;++i){var setResult=true;for(var x in param[i]){var result=$.validator.methods[x].call(this,val,el,param[i][x]);if(!result){setResult=false;break;}}
if(setResult==true){valid=true;break;}}
return this.optional(el)||valid;},"The value entered is invalid");$.validator.addMethod("EmailValidation",function(value,element){return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value);},"Please enter a  valid email id ");$(function(){$("#requestform").validate({ignore:".ignore",rules:{'Name':{required:true,minlength:3,},'Email':{required:true,EmailValidation:true,},'Country':{required:true,},hiddenRecaptcha:{required:function(){if(grecaptcha.getResponse()==''){return true;}else{return false;}}}},submitHandler:function(form){$('#SendQuery').val('Sending...');$("#SendQuery").prop("disabled",true);var Name=$("#Name").val();var Email=$("#Email").val();var Country=$("#Country").val();var Title=$("#Title").val();var Company=$("#Company").val();$.ajax({type:"POST",data:{Name:Name,Email:Email,Country:Country,Title:Title,Company:Company},url:'common/enquiryFrom.php',success:function(output){if(output=1)
{$("#success").show();$('#SendQuery').val('Request a Demo');$("#SendQuery").prop("disabled",false);}
if(output=0)
{$("#failed").show();$('#SendQuery').val('Request a Demo');$("#SendQuery").prop("disabled",false);}
$('#requestform')[0].reset();}});}});});$(function(){$("#contactForm").validate({rules:{'name':{required:true,minlength:3,},'emailid':{required:true,EmailValidation:true,},'mobile':{mvalid:[{required:true,digits:true,}]},reCaptcha:{reCaptchaMethod:true}},submitHandler:function(form){$('#SendQuery').val('Sending...');$('#loading-indicator').show();$("#SendQuery").prop("disabled",true);var name=$("#name").val();var emailid=$("#emailid").val();var mobile=$("#mobile").val();var message=$("#message").val();var recaptchaRes=grecaptcha.getResponse();$.ajax({type:"POST",data:{name:name,emailid:emailid,mobile:mobile,message:message,recaptchaRes:recaptchaRes,},url:'model/contact_us.php',success:function(output){if(output==1)
{$("#success").show();$('#SendQuery').val('Submit');$("#SendQuery").prop("disabled",false);$('#contactusmessage').html('Message successfully sent.').show();setTimeout(function(){$('#contactusmessage').hide();},10000);$('#contactForm')[0].reset();grecaptcha.reset();$('#loading-indicator').hide();}
else if(output==0)
{$("#failed").show();$('#SendQuery').val('Submit');$('#contactusmessage').html('Message not sent, Try Again.').show();setTimeout(function(){$('#contactusmessage').hide();},10000);$("#SendQuery").prop("disabled",false);$('#loading-indicator').hide();}
else if(output==2)
{$("#already").show();$('#SendQuery').val('Submit');$('#contactusmessage').html('Invalid Captcha, Please try again.').show();setTimeout(function(){$('#contactusmessage').hide();},10000);$("#SendQuery").prop("disabled",false);$('#loading-indicator').hide();}}});}});});$(function(){$("#contactus").validate({rules:{'cname':{required:true,minlength:3,},'cemail':{required:true,EmailValidation:true,},},});});$(function(){$("#subscribe-form").validate({rules:{'email':{required:true,EmailValidation:true,},},submitHandler:function(form){$('#SendQueryNews').val('Sending...');$("#SendQueryNews").prop("disabled",true);var email=$("#email").val();$.ajax({type:"POST",data:{email:email},url:site_path+'model/subscribe.php',success:function(output){if(output==1)
{$("#success1").show();$('#SignUpEmail').val('Submit');$("#SignUpEmail").prop("disabled",false);$("#success1").delay(5000).fadeOut();}
else if(output==0)
{$("#failed1").show();$('#SignUpEmail').val('Submit');$("#SignUpEmail").prop("disabled",false);$("#failed1").delay(5000).fadeOut();}
else if(output==2)
{$("#already").show();$('#SignUpEmail').val('Submit');$("#SignUpEmail").prop("disabled",false);$("#already").delay(5000).fadeOut();}
$('#subscribe-form')[0].reset();}});}});});$.validator.addMethod("myFile",function(value,element){return this.optional(element)||/(?:\/|\\)((?:[a-z0-9])*\.(?:pdf|doc|docx))$/i.test(value);},"Incorect file type");$("#add_auth").validate({rules:{'authorisation_Title':{required:true,minlength:3,maxlength:100},'image':{myFile:true,},},});$(function(){$("#apply_job").validate({rules:{'full_name':{required:true,minlength:3,maxlength:40,},'fathers_name':{required:true,minlength:3,maxlength:40,},'date_of_birth':{required:true,},'email_id':{required:true,EmailValidation:true,},'mobile_no':{required:true,minlength:8,maxlength:12,digits:true},'nationality':{required:true,minlength:3,},'optSex':{required:true,},'full_address':{required:true,minlength:3,maxlength:150,},'degree1':{required:true,minlength:3,maxlength:40,},'passing_year1':{required:true,},'percentage_marks1':{required:true,minlength:2,maxlength:2,digits:true},'univ_board1':{required:true,minlength:3,maxlength:70,},'degree2':{minlength:3,maxlength:40,},'percentage_marks2':{minlength:2,maxlength:2,digits:true},'univ_board2':{minlength:3,maxlength:70,},'degree3':{minlength:3,maxlength:40,},'percentage_marks3':{minlength:2,maxlength:2,digits:true},'univ_board3':{minlength:3,maxlength:70,},'degree4':{minlength:3,maxlength:40,},'percentage_marks4':{minlength:2,maxlength:2,digits:true},'univ_board4':{minlength:3,maxlength:70,},'organisation1':{minlength:3,maxlength:40,},'designation1':{minlength:3,maxlength:40,},'period_from1':{minlength:10,maxlength:10,},'period_to1':{minlength:10,maxlength:10,},'last_drawn_salary1':{minlength:3,maxlength:10,digits:true},'organisation2':{minlength:3,maxlength:40,},'designation2':{minlength:3,maxlength:40,},'period_from2':{minlength:10,maxlength:10,},'period_to2':{minlength:10,maxlength:10,},'last_drawn_salary2':{minlength:3,maxlength:10,digits:true},'organisation3':{minlength:3,maxlength:40,},'designation3':{minlength:3,maxlength:40,},'period_from3':{minlength:10,maxlength:10,},'period_to3':{minlength:10,maxlength:10,},'last_drawn_salary3':{minlength:3,maxlength:10,digits:true},'organisation4':{minlength:3,maxlength:40,},'designation4':{minlength:3,maxlength:40,},'period_from4':{minlength:10,maxlength:10,},'period_to4':{minlength:10,maxlength:10,},'last_drawn_salary4':{minlength:3,maxlength:10,digits:true},'registration_no':{minlength:3,maxlength:30,},'current_employment':{minlength:3,maxlength:50,},'relatives':{minlength:3,maxlength:50,},'registration_no':{minlength:3,maxlength:30,},'expected_emoluments':{required:true,minlength:3,maxlength:10,digits:true},'your_current_role':{required:true,minlength:3,maxlength:500,},'resume':{required:true,extension:"DOC|DOCX|doc|docx|rtf|doc|pdf|PDF",filesize:8000000},},messages:{'resume':{'extension':"Allowed file type: (doc,docx,pdf)   max file size: 8 mb",'filesize':"File size should be greater than 1 kb and less than 8 mb",},},});});$.validator.addMethod('filesize',function(value,element,arg){var file_size=element.files[0].size;var minsize=1000;if((file_size>minsize)&&(file_size<=arg)){return true;}else{return false;}});