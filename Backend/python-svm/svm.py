from svmutil import *

entradasT = []
salidasT = []

def leerDatos():

    #Leer los datos del rectangulo
    for i in range(540):
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

        entradasT.append(instancia);
        salidasT.append("Rect");
        print "leido " + str(i) + " de 1"

    for i in range(270):
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

        entradasT.append(instancia);
        salidasT.append("Sqt");
        print "leido " + str(i) + " de 2"

    for i in range(360):
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

        entradasT.append(instancia);
        salidasT.append("Tran");
        print "leido " + str(i) + " de 3"

leerDatos()
exit(1)
cantPartes = 10
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
    m = svm_train(prob, '-t 0 -s 1')   
    p_label, p_acc, p_val = svm_predict(salidasPrueba, entradasPrueba, m)
    precisiones.append(p_acc[0])

precisionFinal = sum(precisiones)/len(precisiones)
print "Precision Final: " + str(precisionFinal)
#prob = svm_problem(salidasT,entradasT)
#m = svm_train(prob, '-v 140') 