const addForm = document.getElementById("add_form");
const editForm = document.getElementById("edit_form");

if(addForm) {
    addForm.addEventListener("submit",
    e => {
        e.preventDefault();
        const formData = new FormData(addForm);
        document.getElementById("checkfirstname").innerHTML = "";
        document.getElementById("checklastname").innerHTML = "";
        document.getElementById("checkemail").innerHTML = "";
        document.getElementById("checkpass").innerHTML="";
        fetch('./treatment/add-randonneur.php', {
            body: formData,
            method: "post"
        })
        .then(response => response.json())
        .then(datas => {
            if(datas.validation==true) {location.href='randonneurs.php';}
            datas.erreurs.forEach((data) => {
                if(data.nom) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.nom;
                    document.getElementById("checkfirstname").appendChild(champ);
                }
                if(data.prenom) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.prenom;
                    document.getElementById("checklastname").appendChild(champ);
                }
                if(data.email) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.email;
                    document.getElementById("checkemail").appendChild(champ);
                }
                if(data.password) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.password;
                    document.getElementById("checkpass").appendChild(champ);
                }
            });
        });
    });
}
if(editForm) {
    editForm.addEventListener("submit",
    e => {
        e.preventDefault();
        const formData = new FormData(editForm);
        document.getElementById("checkfirstname").innerHTML = "";
        document.getElementById("checklastname").innerHTML = "";
        document.getElementById("checkemail").innerHTML = "";
        document.getElementById("checkpass").innerHTML="";
        fetch('./treatment/edit-randonneur.php', {
            body: formData,
            method: "post"
        })
        .then(response => response.json())
        .then(datas => {
            if(datas.validation==true) {location.href='randonneurs.php';}
            datas.erreurs.forEach((data) => {
                if(data.nom) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.nom;
                    document.getElementById("checkfirstname").appendChild(champ);
                }
                if(data.prenom) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.prenom;
                    document.getElementById("checklastname").appendChild(champ);
                }
                if(data.email) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.email;
                    document.getElementById("checkemail").appendChild(champ);
                }
                if(data.password) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.password;
                    document.getElementById("checkpass").appendChild(champ);
                }
            });
        });
    });
}