var f = document.getElementById('file');
var reader = new FileReader();
var img = document.getElementById('preview');





f.addEventListener('change', function(){
    filePreview(this);
});

function filePreview(input) {

    

    if (input.files && input.files[0]) {

        

        reader.readAsDataURL(input.files[0]);

        

        reader.addEventListener('load', function(e){

            var img = document.getElementById('preview');
            img.src = e.target.result;
           
            

        });

   

}



}