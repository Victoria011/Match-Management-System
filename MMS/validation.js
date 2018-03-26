//check if required info has been filled
function validate_required(field, errmsg) {
   with(field) {
      if (value == null || value == "")
      {
         document.getElementById("errMsg").innerHTML=errmsg+" cannot be empty.";
         document.getElementById("phpmsg").innerHTML="";
         return false;
      }
      else { return true }
   }
}

//check length
function validate_length(field, minlength, maxlength, errfield) {
   with(field) {
      if((value.length < minlength) || (value.length > maxlength))
      {
         document.getElementById("errMsg").innerHTML="Invalid "+errfield+" length.";
         document.getElementById("phpmsg").innerHTML="";
         return false;
      }
      else {return true}
   }
}

function validate_form(thisform) {
   with (thisform) {
      if (validate_required(name, "Name") == false ||
          validate_required(email, "Email") == false ||
          validate_required(username, "Username") == false ||
          validate_required(password, "Password") == false ||
          validate_required(confirm_password, "Comfirm Password") == false) {
         return false;
      }
      else if(validate_length(name, 2, 255, "name") == false ||
              validate_length(email, 2, 255, "email") == false ||
              validate_length(phone, 0,25, "phone") == false ||
              validate_length(position, 1, 25, "position") == false ||
              validate_length(username, 1, 50, "username") == false ||
              validate_length(password, 1, 255, "password") == false)
      {return false}
   }
}

function validation(thisform){
   ret = validate_form(thisform);
   if(ret == false) {
      return false;
   }else{
      return true;
   }
}
