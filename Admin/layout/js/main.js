// Get info of aritcles page
let nameImg    = document.querySelector(".name-img"),
    sizeImg    = document.querySelector(".size-img"),
    articleImg = document.getElementById("article_img"),
    urlImg     = "";

// Add img to article and display it
articleImg && articleImg.addEventListener("change", () => {

    const fileReader = new FileReader();

    fileReader.addEventListener("load", () => {
        
        urlImg = fileReader.result;

        document.querySelector(".article-img").src = urlImg;

    });

    fileReader.readAsDataURL(articleImg.files[0]);

});

// Toggle menu
let menu = document.querySelector(".menu");
menu && menu.addEventListener("click", () => { document.querySelector(".navbar ul").classList.toggle("open") });