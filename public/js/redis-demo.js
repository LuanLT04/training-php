// Simple localStorage + Redis demo
(function () {
  var keyInput = document.getElementById('demo-key');
  var valueInput = document.getElementById('demo-value');
  var saveLocalBtn = document.getElementById('save-local');
  var loadLocalBtn = document.getElementById('load-local');
  var saveServerBtn = document.getElementById('save-server');
  var loadServerBtn = document.getElementById('load-server');
  var output = document.getElementById('demo-output');

  function log(msg) {
    output.textContent = msg;
  }

  saveLocalBtn.addEventListener('click', function () {
    var k = keyInput.value || 'foo';
    var v = valueInput.value || 'bar';
    localStorage.setItem(k, v);
    log('Saved locally: ' + k + ' = ' + v);
  });

  loadLocalBtn.addEventListener('click', function () {
    var k = keyInput.value || 'foo';
    var v = localStorage.getItem(k);
    log('Loaded locally: ' + k + ' = ' + v);
    valueInput.value = v || '';
  });

  saveServerBtn.addEventListener('click', function () {
    var k = keyInput.value || 'foo';
    var v = valueInput.value || 'bar';
    fetch('redis_api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ key: k, value: v })
    }).then(function (r) { return r.json(); }).then(function (j) {
      if (j.ok) log('Saved to Redis: ' + k + ' = ' + v);
      else log('Error: ' + (j.error || 'unknown'));
    }).catch(function (e) { log('Error: ' + e.message); });
  });

  loadServerBtn.addEventListener('click', function () {
    var k = keyInput.value || 'foo';
    fetch('redis_api.php?key=' + encodeURIComponent(k))
      .then(function (r) { return r.json(); })
      .then(function (j) {
        if (j && 'value' in j) {
          log('Loaded from Redis: ' + k + ' = ' + j.value);
          valueInput.value = j.value || '';
        } else {
          log('Error: ' + (j.error || 'unknown'));
        }
      })
      .catch(function (e) { log('Error: ' + e.message); });
  });
})();



