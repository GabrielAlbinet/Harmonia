import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.js-favorite-btn').forEach((button) => {
        button.addEventListener('click', async () => {
            try {
                const response = await fetch(`/favorite/toggle/${button.dataset.id}`, { method: 'POST' });
                const { favorited } = await response.json();

                button.textContent = favorited ? 'Retirer des favoris' : 'Ajouter aux favoris';
                button.classList.toggle('btn-danger', favorited);
                button.classList.toggle('btn-success', !favorited);
            } catch (error) {
                console.error('Erreur toggle favori :', error);
            }
        });
    });

    document.querySelectorAll('.js-play-btn').forEach((button) => {
        button.addEventListener('click', async () => {
            try {
                const response = await fetch(`/track/${button.dataset.id}/play`, { method: 'POST' });
                const { playCount, lastListenedAt } = await response.json();

                const row = button.closest('li');
                row.querySelector('.js-play-count').textContent = playCount;

                if (lastListenedAt) {
                    row.querySelector('.js-last-listened').textContent = `Écouté le ${lastListenedAt}`;
                }
            } catch (error) {
                console.error('Erreur play :', error);
            }
        });
    });
});