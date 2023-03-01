var CryptoJSAesJson = {
    /**
     * Encrypt any value
     * @param {*} value
     * @param {string} password
     * @return {string}
     */
    'encrypt': function (value, password) {
        return CryptoJS.AES.encrypt(JSON.stringify(value), password, { format: CryptoJSAesJson }).toString()
    },
    /**
     * Stringify cryptojs data
     * @param {Object} cipherParams
     * @return {string}
     */
    'stringify': function (cipherParams) {
        var j = { ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64) }
        if (cipherParams.iv) j.iv = cipherParams.iv.toString()
        if (cipherParams.salt) j.s = cipherParams.salt.toString()
        return JSON.stringify(j).replace(/\s/g, '')
    },
}
function check() {
    const password = document.getElementById("password").value;
    if (password !== null) {
        var encrypted = CryptoJSAesJson.encrypt(
            document.getElementById("password").value,
            key
        );
        document.getElementById("password").value = encrypted;
        return true;
    } else {
        return false;
    }

}