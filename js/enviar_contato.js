document.addEventListener('DOMContentLoaded', () => {
    // O validacao.js já cuida de impedir o envio se os dados estiverem errados.
    const form = document.getElementById('contatoForm'); 
    
    if (!form) return;

    // Adicionamos o listener de SUBMIT
    form.addEventListener('submit', async function(e) {
        // Se a validação do validacao.js passar, o código continua aqui.
        // Se a validação falhar, o validacao.js já chamou e.preventDefault()
        
        const btn = form.querySelector('button');
        const originalText = btn.textContent;
        btn.textContent = 'Enviando...';
        btn.disabled = true;

        const formData = new FormData(form);

        try {
            const response = await fetch('php/enviar_contato.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                alert(data.message);
                form.reset(); // Limpa o formulário no sucesso
            } else {
                alert('Falha no envio: ' + data.message);
            }
        } catch (error) {
            console.error('Erro de rede:', error);
            alert('Erro de conexão com o servidor.');
        } finally {
            btn.textContent = originalText;
            btn.disabled = false;
        }
    });
});