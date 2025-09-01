import java.util.Random;
import java.util.Scanner;

/**
 * Javaプログラミング 第9回レポート
 * すごろく:レースゲーム（簡潔版）
 * 
 * @author Cyber Java
 */
class Player {
    int position;
    int hitpoint;
    boolean goalNum;
    boolean skipNextTurn;
    String name;

    Player(String name) {
        this.position = 0;
        this.hitpoint = 20;
        this.goalNum = false;
        this.skipNextTurn = false;
        this.name = name;
    }

    void showStatus() {
        System.out.print(name + " 現在の位置: " + position);
        System.out.println(" | 体力値: " + hitpoint);
    }
}

public class CU2001010607_java_report13 {

    static final int MAX_MASS = 21;
    static final int DAMAGE = 2;

    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);
        Random random = new Random();

        System.out.println("すごろくゲームへようこそ！");
        System.out.println("プレイヤーは最大4人まで参加");

        int numPlayers;
        do {
            System.out.print("playerの人数(1〜4)を入力してください: ");
            numPlayers = scanner.nextInt();
        } while (numPlayers < 1 || numPlayers > 4);

        Player[] players = new Player[numPlayers];
        for (int i = 0; i < numPlayers; i++) {
            players[i] = new Player("プレイヤー" + (i + 1));
        }

        scanner.nextLine(); // バッファクリア
        boolean isGameOver = false;
        int eliminatedCount = 0;
        int turn = 0;

        System.out.println("Enterキーを押してゲームを開始します。");
        scanner.nextLine();

        while (!isGameOver) {
            turn++;
            for (Player player : players) {

                if (player.goalNum) continue; // ゴール済みスキップ
                if (player.skipNextTurn) {
                    System.out.println(player.name + "は前回休みのためスキップします。\n");
                    player.skipNextTurn = false;
                    continue;
                }

                System.out.println("--------ターン" + turn + "----------");
                System.out.println(player.name + "の番です。Enterキーを押してサイコロを振ってください...");
                scanner.nextLine();

                int diceRoll = random.nextInt(6) + 1;
                System.out.println("サイコロの目は " + diceRoll + " です。");

                // 体力不足なら回復
                if (player.hitpoint <= diceRoll && player.position + diceRoll < MAX_MASS - 1) {
                    System.out.println("体力不足のためサイコロ分だけ回復します。");
                    player.hitpoint += diceRoll;
                } else {
                    player.position += diceRoll;
                    player.hitpoint -= diceRoll;
                }

                handleSpecialMass(player, diceRoll, scanner);

                // 上限補正
             // 上限補正（体力が0で脱落していない場合のみ）
                if (player.hitpoint > 0 && player.position >= MAX_MASS - 1) {
                    player.position = MAX_MASS - 1;
                }
                if (player.hitpoint < 0) player.hitpoint = 0;

                player.showStatus();
                printMass(players);

             // ゴール判定または脱落判定
                if (!player.goalNum && (player.position >= MAX_MASS - 1 || player.hitpoint <= 0)) {
                    player.goalNum = true;

                    if (player.hitpoint > 0 && player.position >= MAX_MASS - 1) {
                        System.out.println(player.name + "が1位でゴールしました！おめでとう！！\n");
                        isGameOver = true;
                        break;
                    } else if (player.hitpoint <= 0 && player.position >= MAX_MASS - 1) {
                        System.out.println(player.name + "はゴールマスに到達しましたが、体力0のため脱落です…。");
                        eliminatedCount++;
                        System.out.println("脱落人数: " + eliminatedCount + "\n");
                    } else { // 体力0でゴールしていない場合
                        System.out.println(player.name + "は体力0で脱落しました。");
                        eliminatedCount++;
                        System.out.println("脱落人数: " + eliminatedCount + "\n");
                    }
                }
                
                // 全員ゴールまたは脱落でゲーム終了判定
                boolean allFinished = true;
                for (Player p : players) {
                    if (!p.goalNum) {
                        allFinished = false;
                        break;
                    }
                }
                if (allFinished) {
                    isGameOver = true;
                }

            }
        }

        System.out.println("ゲーム終了！");
        System.out.println("最終脱落人数: " + eliminatedCount);
        scanner.close();
    }

    // 特殊マス処理
    static void handleSpecialMass(Player player, int diceRoll, Scanner scanner) {
        // 一回休みマス
        if (player.position % 5 == 0 && player.position != MAX_MASS - 1) {
            System.out.println(player.name + "は一回休みマスに止まりました。次のターンは休みです。\n");
            player.skipNextTurn = true;
        }
        // 回復 or 追加マス
        else if (player.position == 7 || player.position == 12 || player.position == 17) {
            System.out.println(player.name + "は回復マスです。体力+3 か サイコロ追加を選択してください。");
            System.out.print("1: 体力+3  2: サイコロ追加 → ");
            int choice = scanner.nextInt();
            if (choice == 1) {
                player.hitpoint += 3;
                System.out.println(player.name + "の体力が回復しました。HP: " + player.hitpoint + "\n");
            } else {
                int extra = (int)(Math.random() * 6) + 1;
                player.position += extra;
                System.out.println(player.name + "は追加で" + extra + "マス進みました。現在地: " + player.position + "\n");
            }
            scanner.nextLine(); // 改行処理
        }
        // ダメージマス
        else if (player.position == 8 || player.position == 16) {
            player.hitpoint -= DAMAGE;
            System.out.println(player.name + "はダメージマスに止まりました。HP: " + player.hitpoint + "\n");
            if (player.hitpoint <= 0) {
                System.out.println(player.name + "は力尽きました！");
            }
        }
    }

    // プレイヤー位置表示
    static void printMass(Player[] players) {
        for (Player p : players) {
            System.out.print(p.name + ": ");
            for (int i = 0; i < MAX_MASS; i++) {
                System.out.print(i == p.position ? "●" : "□");
            }
            System.out.println("\n");
        }
    }
}
