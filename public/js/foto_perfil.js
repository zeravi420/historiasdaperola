
        const fotoPerfil = document.getElementById('fotoPerfil');
        const previewFoto = document.getElementById('previewFoto');


        fotoPerfil.addEventListener('change', function () {

            const arquivo = this.files[0];


            if (arquivo) {

                const leitor = new FileReader();


                leitor.onload = function (e) {

                    previewFoto.src = e.target.result;

                };


                leitor.readAsDataURL(arquivo);

            }

        });