const discord = require('discord.js')
const CryptoJS = require('crypto-js');
const client = new discord.Client();
const { prefix, token } = require("./config.json");
const { Pool, Client } = require("pg");
var version = 2;
const pool = new Pool({
	user: "postgres",	// SQL SETTINGS ajustamos essas linhas de acordo com nosso próprio servidor
	host: "127.0.0.1",
	database: "postgres",
	password: "adm99",
	port: "5432"
});
// Configurações de conexão
await message.channel.send(`Insira nome de usuário`);
const user = {};
let cp = message.channel.createMessageCollector(x => x.author.id == message.author.id, { max: 1 })
    .on('collect', async c => {
        user.userLogin = c.content
        await message.channel.send(`Digite a senha`);
        message.channel.createMessageCollector(x => x.author.id == message.author.id, { max: 1 })
            .on('collect', async c => {
                user.userPassword = c.content;
                await message.channel.send(`Insira o endereço de e-mail.`);
                let ck = message.channel.createMessageCollector(x => x.author.id == message.author.id, { max: 1 })
                    .on('collect', async c => {
                        user.userMail = c.content
                        var newPass = user.userPassword;
                        var hash = CryptoJS.SHA256(newPass); //Senha introduzida
                        var pwdhash = (hash.toString(CryptoJS.enc.Hex).toUpperCase());
                        user.pwdhash = pwdhash;
                        user.discord = message.author.id;
                        try {
                            // verificar se e-mail ou senha já foram cadastrados;
                            await insertDB(user);
                            await message.channel.send(`Registro bem-sucedido${user.userLogin}.`);
                        } catch (error) {

                            console.log(error);
                            await message.channel.send(`algo deu errado  ${user.userLogin}.`);

                        }
                    })
            })
    });

const insertDB = async ({ userLogin, pwdhash, userMail }) => {
return pool.query("INSERT INTO hops.account(user_name, password, user_type, mail) VALUES($1, $2, 1, $3)", [userLogin, pwdhash, userMail])
}
