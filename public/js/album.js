$('#add-picture').click(function(){
    // récupération du numéro des champs à créer
    const index = +$('#widgets-counter').val();

    // const index = $('#album_pictures div.form-group').length;
    
    // récupération du prototype des entrées
    const tmpl = $('#album_pictures').data('prototype').replace(/__name__/g, index);

    // injection du code dans la div
    $('#album_pictures').append(tmpl);

    $('widgets-counter').val(index + 1);

    // gestion du bouton supprimer
    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function() {
        const target = this.dataset.target;
        console.log(target);
        $(target).remove();
    })
}

function updateCounter() {
    const count = +$('#album_pictures div.form-group').length;

    $('#widgets-counter').val(count);
}
updateCounter();
handleDeleteButtons();