var loadConfig = function () {
    return new Promise(async (resolve, reject) => {
        // run this *after* including the forge script
        forge.options.usePureJavaScript = true;
        //Generar Key
        const key = forge.random.getBytes(16);
        const exportedKey = forge.util.encode64(key);

        const iv = forge.random.getBytesSync(16);
        const exportedIV = forge.util.encode64(iv);
        //const key = await window.crypto.subtle.generateKey({name: 'AES-CBC', length: 128}, true, ['encrypt','decrypt']);
        //const keyPair = await window.crypto.subtle.exportKey('raw', key);
        //const exportedKey = bufferABase64(keyPair);
        $.ajax({
            async: false,
            data: {'key': exportedKey, 'iv': exportedIV},
            type: 'post',
            url: '../controller/Config.controller.php',
            success: async function(response){
               data = JSON.parse(response);

                // Decodificar los datos y la clave cifrados
                const cipherText = new Uint8Array(base64ABuffer(data.data));
                //const iv = new Uint8Array(base64ABuffer(data.iv));
                //const key = new Uint8Array(base64ABuffer(data.key));

                // Descifrar los datos con la clave compartida
                //const keyImport = await window.crypto.subtle.importKey('raw', key, {name: 'AES-CBC'}, true, ['encrypt','decrypt']);
                const decipher = forge.cipher.createDecipher('AES-CBC', key);
                decipher.start({iv: iv});
                decipher.update(forge.util.createBuffer(cipherText));
                decipher.finish();
                const plainText = decipher.output;
                //const plainText = await window.crypto.subtle.decrypt({name: 'AES-CBC', iv: iv}, key, cipherText);
                //const jsonData = JSON.parse(new TextDecoder('utf-8').decode(plainText));
                const jsonData = JSON.parse(plainText.data);
                resolve(jsonData)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error al cargar el archivo JSON:', textStatus, errorThrown);
                reject();
            }
        });

    })
}

const bufferABase64 = buffer => btoa(String.fromCharCode(...new Uint8Array(buffer)));
const base64ABuffer = buffer => Uint8Array.from(atob(buffer), c => c.charCodeAt(0));