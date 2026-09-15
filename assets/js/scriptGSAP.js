document.addEventListener("DOMContentLoaded", () => {
  if (typeof gsap === "undefined" || typeof MotionPathPlugin === "undefined")
    return;

  gsap.registerPlugin(MotionPathPlugin);

  const PATH_SELECTOR = "#path4259";
  const SEPARACION_COCHES = 0.012;
  const VUELTAS = 1;
  const SENSIBILIDAD_SETUP = 9;
  const RETARDO_LUZ_MS = 750;
  const RETARDO_VERDE_MS = 650;
  const ESPERA_SALIDA_MIN_MS = 700;
  const ESPERA_SALIDA_MAX_MS = 2500;
const REACCION_MIN = 0.20;
const REACCION_MAX = 0.60;

  const coches = Array.from(document.querySelectorAll(".f1-car"));
  const circuitoSvg = document.querySelector(".circuito-svg");
  const selectCocheA = document.getElementById("cocheA");
  const selectCocheB = document.getElementById("cocheB");
  const botonStart = document.getElementById("start");
  const botonStop = document.getElementById("stop");
  const botonReset = document.getElementById("reset");
  const raceStatus = document.getElementById("race-status");
  const raceLights = Array.from(document.querySelectorAll(".race-light"));
  const raceGoLight = document.querySelector(".race-go-light");
  const raceStartPanel = document.querySelector(".race-start-panel");
  const tablaResultados = document.getElementById("tabla-resultados-carrera");
  const raceTimers = Array.from(document.querySelectorAll(".race-timers"));
  const timerCoche1 = Array.from(document.querySelectorAll(".timer-coche1"));
  const timerCoche2 = Array.from(document.querySelectorAll(".timer-coche2"));
  const timerLabel1 = Array.from(document.querySelectorAll(".timer-label-coche1"));
  const timerLabel2 = Array.from(document.querySelectorAll(".timer-label-coche2"));

  // Reacciones visuales de los coches.
  const reactionCoche1 = Array.from(document.querySelectorAll("#reaction-coche1, .reaction-coche1"));
  const reactionCoche2 = Array.from(document.querySelectorAll("#reaction-coche2, .reaction-coche2"));

  if (
    !coches.length ||
    !circuitoSvg ||
    !botonStart ||
    !botonStop ||
    !botonReset
  )
    return;

  // Normaliza el punto de inicio a un valor entre 0 y 1 (0 = inicio, 1 = fin del path SVG)
  const meta = Number(circuitoSvg.dataset.puntoInicio ?? 0) % 1;

  let animaciones = [];
  let salidaPendiente = null;
  let resultados = [];
  let carreraFinalizada = false;

  // Calcula el punto inicial de cada coche sobre el SVG, separando el segundo.
  // El % 1 asegura que siempre devuelve un valor entre 0 y 1 (evita que se salga del path).
  function obtenerInicio(indice) {
    return (meta + indice * SEPARACION_COCHES) % 1;
  }

  // Asigna el color del punto segun el coche seleccionado.
  function colorPorCoche(nombre) {
    const normalizado = nombre.toLowerCase();

    if (normalizado.includes("ferrari")) return "#e10600";
    if (normalizado.includes("red bull")) return "#0b1f5e";
    if (normalizado.includes("mercedes")) return "#00d2be";
    if (normalizado.includes("aston")) return "#006f62";

    return "#111111";
  }

  // Lee del select el coche, setup y valores normalizados para la formula.
  function obtenerSetup(id) {
    const select = id === "coche1" ? selectCocheA : selectCocheB;
    const option = select?.selectedOptions?.[0];

    return {
      idSetup: Number(option?.value ?? 0),
      idCoche: Number(option?.dataset.idCoche ?? 0),
      coche: option?.dataset.nombre ?? id,
      setup: option?.dataset.setup ?? "Setup",
      aero: Number(option?.dataset.aero ?? 1),
      altura: Number(option?.dataset.altura ?? 1),
    };
  }

  // Calcula el tiempo de pista comparando los setup con los factores del circuito.
  function calcularTiempo(id) {
    const setup = obtenerSetup(id);
    const tiempoBase = Number(circuitoSvg.dataset.tiempoBase ?? 90);
    const factorAero = Number(circuitoSvg.dataset.factorAero ?? 1);
    const factorAltura = Number(circuitoSvg.dataset.factorAltura ?? 1);
    const diferenciaAero = Math.abs(setup.aero - factorAero);
    const diferenciaAltura = Math.abs(setup.altura - factorAltura);
    const diferenciaTotal = diferenciaAero + diferenciaAltura;

    // Multiplicador que amplifica la penalización según la desviación del setup vs circuito.
    // Cuanto mayor sea la desviación, más castigo recibe el coche.
    let multiplicadorCarencia = 1;

    if (diferenciaTotal >= 1.2) {
      multiplicadorCarencia = 2.4;
    } else if (diferenciaTotal >= 0.8) {
      multiplicadorCarencia = 1.75;
    } else if (diferenciaTotal >= 0.45) {
      multiplicadorCarencia = 1.25;
    }

    // Usa exponente 1.8 para hacer la penalización no-lineal:
    // pequeñas desviaciones castigan poco, grandes desviaciones castigan mucho.
    const penalizacion =
      Math.pow(diferenciaAero, 1.8) * factorAero * SENSIBILIDAD_SETUP +
      Math.pow(diferenciaAltura, 1.8) * factorAltura * SENSIBILIDAD_SETUP;

    return tiempoBase + penalizacion * multiplicadorCarencia;
  }

  // Convierte segundos a formato minuto:segundo.milisegundo.
  // .toFixed(3) obtiene 3 decimales, .padStart(6, "0") rellena con ceros a la izquierda.
  // Ejemplo: 12.5 -> "12:005" (asegura que siempre tenga el mismo ancho visual).
  function formatearTiempo(segundos) {
    const minutos = Math.floor(segundos / 60);
    const resto = segundos - minutos * 60;

    return `${minutos}:${resto.toFixed(3).padStart(6, "0")}`;
  }

  // Sincroniza colores de coches y etiquetas de cronometros con los selects.
  function actualizarIdentidadCoches() {
    const setup1 = obtenerSetup("coche1");
    const setup2 = obtenerSetup("coche2");
    const coche1 = document.getElementById("coche1");
    const coche2 = document.getElementById("coche2");

    if (coche1) coche1.setAttribute("fill", colorPorCoche(setup1.coche));
    if (coche2) coche2.setAttribute("fill", colorPorCoche(setup2.coche));
    timerLabel1.forEach((label) => {
      label.textContent = `Coche 1 - ${setup1.coche}`;
    });
    timerLabel2.forEach((label) => {
      label.textContent = `Coche 2 - ${setup2.coche}`;
    });
  }

  // Actualiza el tiempo de reaccion correspondiente al coche.
  function actualizarReaccion(id, reaccion) {
    const reactions = id === "coche1" ? reactionCoche1 : reactionCoche2;

    reactions.forEach((reaction) => {
      reaction.textContent = `RT: ${reaccion.toFixed(3)}s`;
    });
  }

  // La tabla muestra datos persistidos en BBDD, asi que no se limpia al resetear.
  function limpiarTablaResultados() {}

  // Envia al backend el ganador de la carrera para guardarlo en la BBDD.
  // El ?? (nullish coalescing) devuelve el valor de la derecha si el de la izquierda es null/undefined.
  async function guardarGanador(ganador) {
    const datos = new URLSearchParams({
      id_circuito: circuitoSvg.dataset.idCircuito ?? "0",
      id_coche_ganador: ganador.idCoche,
      tiempo_ganador: ganador.tiempo.toFixed(3),
    });

    resultados.forEach((resultado) => {
      datos.append("ids_setup[]", resultado.idSetup);
    });

    const respuesta = await fetch(circuitoSvg.dataset.guardarCarreraUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: datos,
    });

    if (!respuesta.ok) throw new Error("No se pudo guardar la carrera.");

    return respuesta.json();
  }

  // Crea una celda de tabla usando textContent para evitar HTML dinamico.
  function crearCelda(texto) {
    const celda = document.createElement("td");
    celda.textContent = texto;
    return celda;
  }

  // Devuelve la clase visual del equipo para la tabla de tiempos.
  function claseEquipoTabla(nombre) {
    const normalizado = nombre.toLowerCase();

    if (normalizado.includes("ferrari")) return "team-ferrari";
    if (normalizado.includes("red bull")) return "team-red-bull";
    if (normalizado.includes("mercedes")) return "team-mercedes";
    if (normalizado.includes("aston")) return "team-aston-martin";

    return "team-default";
  }

  // Crea la celda del coche ganador con el color de su equipo.
  function crearCeldaEquipo(nombre) {
    const celda = document.createElement("td");
    const chip = document.createElement("span");

    chip.className = `team-chip ${claseEquipoTabla(nombre)}`;
    chip.textContent = nombre;
    celda.append(chip);

    return celda;
  }

  // Inserta en la tabla el ganador recien guardado sin usar innerHTML.
  // Busca si existe un mensaje "No hay resultados" (que tiene colspan) y lo limpia.
  function insertarGanadorEnTabla(ganador, fechaCarrera) {
    if (!tablaResultados) return;

    const filaVacia = tablaResultados.querySelector("td[colspan]");

    // replaceChildren() sin argumentos limpia todos los hijos de forma segura.
    if (filaVacia) {
      tablaResultados.replaceChildren();
    }

    const fila = document.createElement("tr");
    fila.append(
      crearCelda(circuitoSvg.dataset.nombreCircuito ?? "Circuito"),
      crearCeldaEquipo(ganador.coche),
      crearCelda(formatearTiempo(ganador.tiempo)),
      crearCelda(fechaCarrera),
    );

    // prepend() inserta en la primera posición (últimas carreras arriba, estilo LIFO).
    tablaResultados.prepend(fila);
  }

  // Ordena los resultados, obtiene el ganador y lo persiste.
  async function pintarResultados() {
    if (!tablaResultados || resultados.length < coches.length) return;

    const [ganador] = [...resultados].sort((a, b) => a.tiempo - b.tiempo);

    try {
      const respuesta = await guardarGanador(ganador);
      const fechaFallback = new Date()
        .toISOString()
        .slice(0, 19)
        .replace("T", " ");

      insertarGanadorEnTabla(ganador, respuesta.fecha_carrera ?? fechaFallback);
    } catch (error) {
      if (raceStatus) raceStatus.textContent = "No se pudo guardar el tiempo";
    }
  }

  // Devuelve el semaforo al estado inicial sin hacerlo visible.
  function resetSemaforo(texto = "Preparado") {
    raceStartPanel?.classList.remove("is-visible");
    raceStartPanel?.classList.add("is-hidden");

    raceLights.forEach((light) => light.classList.remove("is-on"));
    raceGoLight?.classList.remove("is-green");

    if (raceStatus) raceStatus.textContent = texto;
  }

  // Muestra el semaforo cuando se pulsa Start.
  function mostrarSemaforo(texto = "Preparado") {
    raceStartPanel?.classList.remove("is-hidden");
    raceStartPanel?.classList.add("is-visible");

    raceLights.forEach((light) => light.classList.remove("is-on"));
    raceGoLight?.classList.remove("is-green");

    if (raceStatus) raceStatus.textContent = texto;
  }

  // Enciende las luces por columnas y lanza la salida tras espera aleatoria.
  // Las luces tienen un atributo data-step que agrupa 5 columnas de luces rojas.
  function prepararSemaforo(callback) {
    mostrarSemaforo("Preparado");

    let paso = 1;
    const totalPasos = 5; // Enciendes 5 columnas progresivamente

    // Función recursiva que se llama a sí misma con delay hasta que termina.
    const encender = () => {
      if (paso <= totalPasos) {
        // Encuentra todas las luces con data-step igual al paso actual.
        raceLights
          .filter((light) => Number(light.dataset.step) === paso)
          .forEach((light) => light.classList.add("is-on"));

        paso += 1;
        // Se programa a sí misma para el siguiente paso con RETARDO_LUZ_MS de espera.
        salidaPendiente = window.setTimeout(encender, RETARDO_LUZ_MS);
        return;
      }

      // Genera un delay aleatorio entre MIN y MAX para que los pilotos no reaccionen al mismo tiempo.
      const esperaAleatoria =
        ESPERA_SALIDA_MIN_MS +
        Math.random() * (ESPERA_SALIDA_MAX_MS - ESPERA_SALIDA_MIN_MS);

      // Cuando todas las luces se encienden, espera el tiempo aleatorio antes de dar salida.
      salidaPendiente = window.setTimeout(() => {
        raceLights.forEach((light) => light.classList.remove("is-on"));
        raceGoLight?.classList.add("is-green");

        if (raceStatus) raceStatus.textContent = "Salida";

        window.setTimeout(() => {
          raceStartPanel?.classList.remove("is-visible");
          raceStartPanel?.classList.add("is-hidden");
          callback();
        }, RETARDO_VERDE_MS);
      }, esperaAleatoria);
    };

    encender();
  }

  // Actualiza el cronometro correspondiente al coche.
  function actualizarTimer(id, segundos) {
    const timers = id === "coche1" ? timerCoche1 : timerCoche2;
    timers.forEach((timer) => {
      timer.textContent = formatearTiempo(segundos);
    });
  }

  // Reinicia ambos cronometros visuales.
  function resetTimers() {
    actualizarTimer("coche1", 0);
    actualizarTimer("coche2", 0);
    actualizarReaccion("coche1", 0);
    actualizarReaccion("coche2", 0);
  }

  // Muestra u oculta los cronometros durante la carrera.
  function mostrarTimers(mostrar) {
    raceTimers.forEach((timerPanel) => {
      timerPanel.classList.toggle("is-hidden", !mostrar);
    });
  }

  // Coloca los coches en la salida de su circuito.
  function colocarCoches() {
    coches.forEach((coche, i) => {
      const inicio = obtenerInicio(i);

      gsap.set(coche, {
        opacity: 1,
        motionPath: {
          path: PATH_SELECTOR,
          align: PATH_SELECTOR,
          alignOrigin: [0.5, 0.5],
          start: inicio,
          end: inicio,
        },
      });
    });
  }

  // Crea las animaciones de carrera con reaccion aleatoria por coche.
  function lanzarCarrera() {
    resultados = [];
    carreraFinalizada = false;
    limpiarTablaResultados();
    resetTimers();

    coches.forEach((coche, i) => {
      const inicio = obtenerInicio(i);
      const setup = obtenerSetup(coche.id);
      const tiempoPista = calcularTiempo(coche.id);
      const reaccion =
        REACCION_MIN + Math.random() * (REACCION_MAX - REACCION_MIN);
      const duracionTotal = tiempoPista + reaccion;

      actualizarReaccion(coche.id, reaccion);

      // delay espera el tiempo de reacción antes de empezar a animar.
      // end: inicio - VUELTAS hace que vuelva atrás 1 vuelta completa (por eso es negativo).
      const anim = gsap.to(coche, {
        delay: reaccion,
        duration: tiempoPista,
        ease: "none",
        motionPath: {
          path: PATH_SELECTOR,
          align: PATH_SELECTOR,
          alignOrigin: [0.5, 0.5],
          start: inicio,
          end: inicio - VUELTAS, // Termina 1 vuelta atrás del punto inicial
        },
        // onUpdate se ejecuta en cada frame de la animación.
        // reaccion + this.time() suma el tiempo de reacción al cronómetro visual.
        onUpdate() {
          actualizarTimer(coche.id, reaccion + this.time());
        },
        onComplete() {
          actualizarTimer(coche.id, duracionTotal);

          resultados.push({
            etiqueta: coche.id === "coche1" ? "Coche 1" : "Coche 2",
            idSetup: setup.idSetup,
            idCoche: setup.idCoche,
            coche: setup.coche,
            setup: setup.setup,
            reaccion,
            tiempo: duracionTotal,
          });

          if (resultados.length === coches.length) {
            carreraFinalizada = true;
            if (raceStatus) raceStatus.textContent = "Carrera finalizada";
            pintarResultados();
          }
        },
      });

      animaciones.push(anim);
    });
  }

  // Inicia una carrera nueva o reanuda una carrera pausada.
  function startCarrera() {
    if (salidaPendiente) return;

    if (animaciones.length && !carreraFinalizada) {
      animaciones.forEach((anim) => anim.resume());
      if (raceStatus) raceStatus.textContent = "Carrera en marcha";
      return;
    }

    resetCarrera();
    botonStart.disabled = true;

    prepararSemaforo(() => {
      salidaPendiente = null;
      botonStart.disabled = false;
      mostrarTimers(true);
      lanzarCarrera();
    });
  }

  // Pausa todas las animaciones activas.
  function stopCarrera() {
    animaciones.forEach((anim) => anim.pause());

    if (raceStatus && animaciones.length && !carreraFinalizada) {
      raceStatus.textContent = "Pausado";
    }
  }

  // Cancela la carrera, resetea semaforo, cronometros y posiciones.
  function resetCarrera() {
    if (salidaPendiente) {
      window.clearTimeout(salidaPendiente);
      salidaPendiente = null;
    }

    animaciones.forEach((anim) => anim.kill());
    animaciones = [];
    resultados = [];
    carreraFinalizada = false;

    botonStart.disabled = false;

    resetSemaforo();
    resetTimers();
    mostrarTimers(false);
    limpiarTablaResultados();
    actualizarIdentidadCoches();
    colocarCoches();
  }

  botonStart.addEventListener("click", startCarrera);
  botonStop.addEventListener("click", stopCarrera);
  botonReset.addEventListener("click", resetCarrera);
  selectCocheA?.addEventListener("change", resetCarrera);
  selectCocheB?.addEventListener("change", resetCarrera);

  resetCarrera();
});
