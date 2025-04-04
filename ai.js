import http from 'http';
import { URLSearchParams } from 'url';

let turn = 0;

function kirimPesan(teksnya, tujuan, callback) {
  const postData = new URLSearchParams({ teksnya }).toString();

  const options = {
    hostname: 'localhost',
    port: 80,
    path: tujuan,
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Content-Length': Buffer.byteLength(postData),
    },
  };

  const req = http.request(options, (res) => {
    res.setEncoding('utf8');
    let jawaban = '';

    res.on('data', (chunk) => {
      process.stdout.write(chunk);
      jawaban += chunk;
    });

    res.on('end', () => {
      callback(jawaban.trim());
    });
  });

  req.on('error', (e) => {
    console.error(`Terjadi error (${tujuan}):`, e);
  });

  req.write(postData);
  req.end();
}

// Fungsi obrolan bolak-balik
function mulaiChat(pesanAwal) {
  function loop(ai1Pesan) {

    turn++;

    console.log("\n========================================================");
    console.log(`AI-1:`);
    kirimPesan(ai1Pesan, '/api/stream1.php', (resAI1) => {
        console.log("\n========================================================");
        console.log(`AI-2:`);
        kirimPesan(resAI1, '/api/stream2.php', (resAI2) => {

            loop(resAI2);
      });
    });
  }

  loop(pesanAwal);
}

mulaiChat('Halo, kamu');
