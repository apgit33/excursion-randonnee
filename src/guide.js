const addForm = document.getElementById("add_form");
const editForm = document.getElementById("edit_form");

if(addForm) {
    addForm.addEventListener("submit",
    e => {
        e.preventDefault();
        const formData = new FormData(addForm);
        document.getElementById("checkfirstname").innerHTML = "";
        document.getElementById("checkphone").innerHTML = "";
        fetch('./treatment/add-guide.php', {
            body: formData,
            method: "post"
        })
        .then(response => response.json())
        .then(datas => {
            if(datas.validation==true) {location.href='guide.php';}
            datas.erreurs.forEach((data) => {
                if(data.nom) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.nom;
                    document.getElementById("checkfirstname").appendChild(champ);
                }
                if(data.phone) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.phone;
                    document.getElementById("checkphone").appendChild(champ);
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
        document.getElementById("checkphone").innerHTML = "";
        fetch('./treatment/edit-guide.php', {
            body: formData,
            method: "post"
        })
        .then(response => response.json())
        .then(datas => {
            if(datas.validation==true) {location.href='guide.php';}
            datas.erreurs.forEach((data) => {
                if(data.nom) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.nom;
                    document.getElementById("checkfirstname").appendChild(champ);
                }
                if(data.phone) {
                    let champ = document.createElement("p");
                    champ.innerHTML = data.phone;
                    document.getElementById("checkphone").appendChild(champ);
                }
                });
                
        });
    });
}