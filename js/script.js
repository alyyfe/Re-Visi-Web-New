// Animasi 
const badge = document.querySelector('.floating-element.bagus-badge');
const heroSection = document.querySelector('.hero-column');

// Fungsi untuk menghasilkan posisi acak dalam batas tertentu
function randomPosition(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function moveRandomWithinHero() {
    const heroRect = heroSection.getBoundingClientRect();
    const badgeRect = badge.getBoundingClientRect();

    const randomLeft = randomPosition(
        0, 
        heroRect.width - badgeRect.width 
    );

    const randomTop = randomPosition(
        0, 
        heroRect.height - badgeRect.height 
    );

    badge.style.transition = 'all 0.5s ease-in-out';
    badge.style.position = 'absolute';
    badge.style.left = `${randomLeft}px`;
    badge.style.top = `${randomTop}px`;
}


function startContinuousRandomMovement() {
    setInterval(() => {
        moveRandomWithinHero(); 
    }, 1000); 
}


startContinuousRandomMovement();


const hamburger = document.getElementById("hamburger-menu");
const navMenu = document.querySelector(".menu");


hamburger.addEventListener("click", (event) => {
    event.preventDefault();
  navMenu.classList.toggle("active");
});




// komentar
window.fbAsyncInit = function() {
    FB.init({
        appId      : '1073892667515275', //facebook app id
        xfbml      : true,
        version    : 'v10.0' 
    });
};

(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); 
    js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


// kartu mirip mac
const kontainerKartu = document.getElementById('kontainer-kartu');
let isPaused = false;


const originalKartu = kontainerKartu.innerHTML;
kontainerKartu.innerHTML += originalKartu; 


document.getElementById('carousel').addEventListener('mouseover', () => {
  if (!isPaused) {
    kontainerKartu.style.animationPlayState = 'paused';
    isPaused = true;
  }
});


document.getElementById('carousel').addEventListener('mouseout', () => {
  if (isPaused) {
    kontainerKartu.style.animationPlayState = 'running';
    isPaused = false;
  }
});


// scroll reveal

const sr = ScrollReveal({
    origin: 'bottom',
    distance: '10px',
    duration: 600,  
    easing: 'ease-in-out',
    delay: 100,     
    reset: true
});

// Reveal elements
sr.reveal('.kartu', { interval: 150 });
sr.reveal('.judul-kartu', { delay: 200 }); 

// sign up
  document.querySelector('form').addEventListener('submit', function (e) {
        const usernameInput = document.getElementById('username');
        if (!usernameInput.value.startsWith('@')) {
            alert('Username harus diawali dengan "@"');
            e.preventDefault(); // Mencegah form dari pengiriman
        }
    });


    // Fungsi untuk membuka tab
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
  
    // Menghilangkan semua isi tab
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
  
    // Menghapus class "active" dari semua tombol
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
  
    // Menampilkan tab yang diklik
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
  }
  
  // Secara default, buka tab pertama (Design)
  document.addEventListener("DOMContentLoaded", function () {
    openTab(event, "Design"); // Memanggil openTab untuk menampilkan tab Design saat halaman dimuat
  });
  
  // Pencarian kartu
  document.getElementById("search").addEventListener("input", function () {
    const query = this.value.toLowerCase(); // Mendapatkan nilai pencarian
    const cards = document.querySelectorAll(".kartu"); // Mengambil semua kartu
  
    cards.forEach((card) => {
      const title = card
        .querySelector("h1")
        .getAttribute("data-title")
        .toLowerCase(); // Mengambil judul dari kartu
      if (title.includes(query)) {
        card.style.display = ""; // Menampilkan kartu jika cocok
      } else {
        card.style.display = "none"; // Menyembunyikan kartu jika tidak cocok
      }
    });
  });


    // Ambil elemen marquee
    const marquee = document.querySelector('.marquee');

    // Duplikasi konten untuk efek looping tanpa henti
    marquee.innerHTML += marquee.innerHTML; // Menambahkan konten marquee dua kali