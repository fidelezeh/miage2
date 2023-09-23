let montant = 15000;
let nom = "John DOE";
let age = 33;

function redirect()
{
    location.href="http://myprojects.test/gap-b23-v1/";
}

console.log("Ceci est un message !!");

function infos()
{
    console.log(nom);
    console.log(age);
    console.log(montant);
}

infos();

function textChanger()
{
    document.getElementById("miage").innerHTML = "Hello Miage2";
}