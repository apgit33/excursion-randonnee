const addForm = document.getElementById("add_form");
const editForm = document.getElementById("edit_form");

if(addForm) {
    addForm.addEventListener("submit",
    e => {
        e.preventDefault();
        const formData = new FormData(addForm);
        document.getElementById("checkfirstname").innerHTML = "";
        document.getElementById("checktarif").innerHTML = "";
        document.getElementById("checkmax").innerHTML = "";
        document.getElementById("checkdp").innerHTML = "";
        document.getElementById("checkda").innerHTML = "";
        fetch('./treatment/add-excursion.php', {
            body: formData,
            method: "post"
        })
        .then(response => response.json())
        .then(datas => {
            if(datas.validation==true) {location.href='excursion.php';}
            datas.erreurs.forEach((data) => {
                if(data.nom) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.nom;
                    document.getElementById("checkfirstname").appendChild(champ);
                }
                if(data.tarif) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.tarif;
                    document.getElementById("checktarif").appendChild(champ);
                }
                if(data.max) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.max;
                    document.getElementById("checkmax").appendChild(champ);
                }
                if(data.dp) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.dp;
                    document.getElementById("checkdp").appendChild(champ);
                }
                if(data.da) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.da;
                    document.getElementById("checkda").appendChild(champ);
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
        document.getElementById("checktarif").innerHTML = "";
        document.getElementById("checkmax").innerHTML = "";
        document.getElementById("checkdp").innerHTML = "";
        document.getElementById("checkda").innerHTML = "";
        fetch('./treatment/edit-excursion.php', {
            body: formData,
            method: "post"
        })
        .then(response => response.json())
        .then(datas => {
            if(datas.validation==true) {location.href='excursion.php';}
            datas.erreurs.forEach((data) => {
                if(data.nom) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.nom;
                    document.getElementById("checkfirstname").appendChild(champ);
                }
                if(data.tarif) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.tarif;
                    document.getElementById("checktarif").appendChild(champ);
                }
                if(data.max) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.max;
                    document.getElementById("checkmax").appendChild(champ);
                }
                if(data.dp) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.dp;
                    document.getElementById("checkdp").appendChild(champ);
                }
                if(data.da) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.da;
                    document.getElementById("checkda").appendChild(champ);
                }
            });
        });
    });
}