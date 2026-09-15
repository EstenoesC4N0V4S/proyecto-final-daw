const circuitos = {
  monza: {
    nombre: "AUTODROMO NAZIONALE MONZA",
    baseSpeed: 355, 
    minSpeed: 335,
    maxSpeed: 362,
    baseLap: 79.5,  
    dfPenalty: 0.008, 
    dragPenalty: 16,
  },
  monaco: {
    nombre: "CIRCUIT DE MONACO",
    baseSpeed: 295,
    minSpeed: 280,
    maxSpeed: 305,
    baseLap: 71.0,  
    dfPenalty: -0.009, 
    dragPenalty: 4,  
  },
  spa: {
    nombre: "CIRCUIT DE SPA-FRANCORCHAMPS",
    baseSpeed: 345,
    minSpeed: 320,
    maxSpeed: 355,
    baseLap: 104.0, 
    dfPenalty: 0.003,
    dragPenalty: 12,
  },
  silverstone: {
    nombre: "SILVERSTONE CIRCUIT",
    baseSpeed: 330,
    minSpeed: 310,
    maxSpeed: 340,
    baseLap: 86.5,  
    dfPenalty: -0.004, 
    dragPenalty: 10,
  },
  suzuka: {
    nombre: "SUZUKA INTERNATIONAL RACING COURSE",
    baseSpeed: 325,
    minSpeed: 305,
    maxSpeed: 335,
    baseLap: 88.5, 
    dfPenalty: -0.006, 
    dragPenalty: 9,
  },
  mexico: {
    nombre: "AUTÓDROMO HERMANOS RODRÍGUEZ",
    baseSpeed: 360, 
    minSpeed: 340,
    maxSpeed: 372,
    baseLap: 77.5, 
    dfPenalty: 0.006, 
    dragPenalty: 14,
  },
  interlagos: {
    nombre: "AUTÓDROMO DE INTERLAGOS",
    baseSpeed: 330,
    minSpeed: 315,
    maxSpeed: 345,
    baseLap: 70.5,  
    dfPenalty: -0.001,
    dragPenalty: 11,
  },
  zandvoort: {
    nombre: "CIRCUIT ZANDVOORT",
    baseSpeed: 315,
    minSpeed: 295,
    maxSpeed: 325,
    baseLap: 70.5,  
    dfPenalty: -0.007, 
    dragPenalty: 8,
  },
  jeddah: {
    nombre: "JEDDAH CORNICHE CIRCUIT",
    baseSpeed: 340,
    minSpeed: 325,
    maxSpeed: 355,
    baseLap: 87.5,  
    dfPenalty: 0.002,
    dragPenalty: 13,
  },
  baku: {
    nombre: "BAKU CITY CIRCUIT",
    baseSpeed: 365, 
    minSpeed: 340,
    maxSpeed: 375,
    baseLap: 100.5, 
    dfPenalty: 0.009,
    dragPenalty: 18,
  },
  austin: {
    nombre: "CIRCUIT OF THE AMERICAS",
    baseSpeed: 330,
    minSpeed: 310,
    maxSpeed: 340,
    baseLap: 94.5,  
    dfPenalty: -0.002,
    dragPenalty: 11,
  },
  marina_bay: {
    nombre: "MARINA BAY STREET CIRCUIT",
    baseSpeed: 310,
    minSpeed: 290,
    maxSpeed: 320,
    baseLap: 90.0,  
    dfPenalty: -0.008,
    dragPenalty: 7,
  },
  red_bull_ring: {
    nombre: "RED BULL RING",
    baseSpeed: 335,
    minSpeed: 320,
    maxSpeed: 345,
    baseLap: 64.5,  
    dfPenalty: 0.005,
    dragPenalty: 12,
  },
};

function generarTelemetria(circuitoKey) {
  const circuito = circuitos[circuitoKey] || circuitos.monza;

  const balanceAero = Math.floor(Math.random() * 41) + 30;
  const downforce = Math.floor(Math.random() * 1001) + 2000;
  const dragCoef = +(0.7 + Math.random() * 0.3).toFixed(2);

  // VELOCIDAD PUNTA
  let velocidadPunta = circuito.baseSpeed;
  velocidadPunta -= (downforce - 2000) * 0.035;
  velocidadPunta -= (dragCoef - 0.7) * 75;

  // límite realista por circuito
  velocidadPunta = Math.max(
    circuito.minSpeed,
    Math.min(circuito.maxSpeed, velocidadPunta),
  );
  velocidadPunta = Math.round(velocidadPunta);

  // LAP TIME
  let lapTime = circuito.baseLap;
  lapTime += (dragCoef - 0.7) * circuito.dragPenalty;
  lapTime += (downforce - 2000) * circuito.dfPenalty;
  lapTime += Math.random() * 2;

  // FORMATO TIEMPO
  const minutos = Math.floor(lapTime / 60);
  const segundos = Math.floor(lapTime % 60);
  const milesimas = Math.floor((lapTime % 1) * 1000);

  const lapEst = `${minutos}:${String(segundos).padStart(2, "0")}.${String(milesimas).padStart(3, "0")}`;

  // PINTAR
  document.getElementById("circuito").textContent = circuito.nombre;
  document.getElementById("balance-aero").textContent = `${balanceAero}% Front`;
  document.getElementById("downforce").textContent =
    `${downforce.toLocaleString("es-ES")} N`;
  document.getElementById("vel-punta").textContent = `${velocidadPunta} km/h`;
  document.getElementById("drag-coef").textContent = `${dragCoef} Cd`;
  document.getElementById("lap-est").textContent = lapEst;
}

// 🔥 CIRCUITO ALEATORIO AL RECARGAR
document.addEventListener("DOMContentLoaded", () => {
  const claves = Object.keys(circuitos);
  const randomCircuito = claves[Math.floor(Math.random() * claves.length)];

  generarTelemetria(randomCircuito);
});
