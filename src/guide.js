const addForm = document.getElementById("add_form");
const editForm = document.getElementById("edit_form");
const verif = document.getElementById("verif");

if(addForm) {
    addForm.addEventListener("submit",
    e => {
        e.preventDefault();
        const formData = new FormData(addForm);
        verif.innerHTML = "";
        fetch('./treatment/add-guide.php', {
            body: formData,
            method: "post"
        })
        .then(response => response.json())
        .then(datas => {
            if(datas.validation==true) {location.href='guide.php';}
            datas.erreurs.forEach((data) => {
                //On créé un créé un élément HTML option
                let champ = document.createElement("li");
                //On affecte la valeur de l'élément créé
                champ.innerHTML = data;
                // console.log(data.Erreur);
                
                //On ajoute en noeud enfant à la datalist l'option créé
                verif.appendChild(champ);
                });
                
        });
    });
}
if(editForm) {
    editForm.addEventListener("submit",
    e => {
        e.preventDefault();
        const formData = new FormData(editForm);
        verif.innerHTML = "";
        fetch('./treatment/edit-guide.php', {
            body: formData,
            method: "post"
        })
        .then(response => response.json())
        .then(datas => {
            if(datas.validation==true) {location.href='guide.php';}
            datas.erreurs.forEach((data) => {
                //On créé un créé un élément HTML option
                let champ = document.createElement("li");
                //On affecte la valeur de l'élément créé
                champ.innerHTML = data;
                // console.log(data.Erreur);
                
                //On ajoute en noeud enfant à la datalist l'option créé
                verif.appendChild(champ);
                });
                
        });
    });
}