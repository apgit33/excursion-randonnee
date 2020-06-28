const form = document.getElementById("form-login");
const verif = document.getElementById("verif");

form.addEventListener("submit",
e => {
    e.preventDefault();
    const formData = new FormData(form);
    verif.innerHTML = "";
    fetch('./treatment/login.php', {
        body: formData,
        method: "post"
    })
    .then(response => response.json())
    .then(datas => {
        if(datas.validation==true) {location.href='dashboard.php';}
        datas.erreurs.forEach((data) => {
            //On créé un créé un élément HTML option
            let champ = document.createElement("li");
            //On affecte la valeur de l'élément créé
            champ.innerHTML = data;
            //On ajoute en noeud enfant à la datalist l'option créé
            verif.appendChild(champ);
            });
            
    });
});