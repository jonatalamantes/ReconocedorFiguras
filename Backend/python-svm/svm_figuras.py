from svmutil import *
import datetime

entradasT = []
salidasT = []

#Ponemos la bandera del comienzo
print ""
print "****** Comienzo con " + str(datetime.datetime.now()) + " **********"
print ""

#Leer los datos del rectangulo
for i in range(36):
    archivo = '../db/Rect/' + str(i+1);
    file = open(archivo, "r")
    instancia = []
    while True:
        letra = file.read(1)
        if not letra:
            break
        elif letra == '\n':
            continue

        instancia.append(int(letra))

    for i in range(10):
        entradasT.append(instancia);
        salidasT.append(1);

for i in range(18):
    archivo = '../db/Sqt/' + str(i+1);
    file = open(archivo, "r")
    instancia = []
    while True:
        letra = file.read(1)
        if not letra:
            break
        elif letra == '\n':
            continue

        instancia.append(int(letra))

    for i in range(10):
        entradasT.append(instancia);
        salidasT.append(2);

for i in range(24):
    archivo = '../db/Tran/' + str(i+1);
    file = open(archivo, "r")
    instancia = []
    while True:
        letra = file.read(1)
        if not letra:
            break
        elif letra == '\n':
            continue

        instancia.append(int(letra))

    for i in range(10):
        entradasT.append(instancia);
        salidasT.append(3);

"""cantPartes = 10
cantPorParte = len(salidasT)/cantPartes
precisiones = []
                   
for posPrueba in range(cantPartes):
    posInicioPrueba = posPrueba*cantPorParte
    posFinPrueba = posInicioPrueba+cantPorParte
    
    #Datos para prueba
    entradasPrueba = entradasT[posInicioPrueba:posFinPrueba]
    salidasPrueba = salidasT[posInicioPrueba:posFinPrueba]
    
    #Datos de entrenamiento
    entradasEntre = entradasT[:]
    salidasEntre = salidasT[:]
    
    #Se borran los datos que se encuentran en los indices de pruebas actuales
    del entradasEntre[posInicioPrueba:posFinPrueba]
    del salidasEntre[posInicioPrueba:posFinPrueba]

    prob = svm_problem(salidasEntre,entradasEntre)
    m = svm_train(prob, '-s 0 -t 2')   
    p_label, p_acc, p_val = svm_predict(salidasPrueba, entradasPrueba, m)
    precisiones.append(p_acc[0])

precisionFinal = sum(precisiones)/len(precisiones)
print "Precision Final: " + str(precisionFinal) """

prob  = svm_problem(salidasT, entradasT)
svm_train(prob, '-s 0 -t 2 -v 10')
model = svm_train(prob, '-s 0 -t 2')

svm_save_model('./svm_figuras.model', model);

#Ponemos la bandera del comienzo
print ""
print "****** Termino con " + str(datetime.datetime.now()) + " **********"
print ""