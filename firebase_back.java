package com.eimbox.eimboxbase;


import android.annotation.SuppressLint;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Build;
import android.os.Handler;
import android.os.Looper;
import android.widget.Toast;

import androidx.core.app.NotificationCompat;

import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

import java.util.Calendar;
import java.util.Map;
import java.util.Objects;

@SuppressLint("MissingFirebaseInstanceTokenRefresh")
public class MyFirebaseMessagingService extends FirebaseMessagingService {
    SharedPreferences stock;
    String pref_ch;

    @SuppressLint("ObsoleteSdkInt")
    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {
        // TODO(developer): Handle FCM messages here.
        // Not getting messages here? See why this may be: https://goo.gl/39bRNJ
        // System.out.println("From: " + remoteMessage.getFrom());
        pref_ch = getString(R.string.pref_ch);

        Intent intent = new Intent(this, PendingActivity.class);
        //intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
        PendingIntent pendingIntent = PendingIntent.getActivity(this, 0, intent,
                PendingIntent.FLAG_ONE_SHOT | PendingIntent.FLAG_IMMUTABLE);
        //int MSGID= remoteMessage.getFrom();
        Calendar mycal = Calendar.getInstance();
        int MSGID = (int) (mycal.getTimeInMillis() / 1000);
        String TITLE = Objects.requireNonNull(remoteMessage.getNotification()).getTitle(); // remoteMessage.getFrom();
        String BODY = remoteMessage.getNotification().getBody();


        Map<String, String> DT = remoteMessage.getData();
        String d1 = DT.get("data1");
        String d2 = DT.get("data2");
        String d3 = DT.get("data3");

        if (!Objects.equals(d1, "") && d1 != null) {
            BODY = BODY + "\r\n" + d1 + "\r\n" + d2 + "\r\n" + d3;
        } else {
            BODY = BODY;
        }
//        BODY = BODY + '\n' + d1 + d2 + d3;

        String channelId = pref_ch;
        Uri defaultSoundUri = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);

        NotificationCompat.Builder notificationBuilder =
                new NotificationCompat.Builder(this, channelId)
                        .setSmallIcon(R.drawable.logo)
                        .setContentTitle(TITLE)
                        .setContentText(BODY)
                        .setStyle(new NotificationCompat.BigTextStyle().bigText(BODY))
                        .setPriority(NotificationManager.IMPORTANCE_MAX)
                        .setAutoCancel(true)
                        .setSound(defaultSoundUri)
                        .setFullScreenIntent(pendingIntent, true)
                        .setContentIntent(pendingIntent);
        NotificationManager notificationManager =
                (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel = new NotificationChannel(channelId,
                    pref_ch,
                    NotificationManager.IMPORTANCE_HIGH);
            notificationManager.createNotificationChannel(channel);
        }
        notificationManager.notify(MSGID, notificationBuilder.build());


        // Check if message contains a notification payload.
//         if (remoteMessage.getNotification() != null) {
//             System.out.println("Message Notification Body: " + remoteMessage.getNotification().getBody());
//         }
        //stock = getSharedPreferences("ROSEPCS",MODE_PRIVATE);
        //SharedPreferences.Editor myEdit = stock.edit();
        //myEdit.putString("noti", remoteMessage.getNotification().getTitle());
        //myEdit.apply();


        // Also if you intend on generating your own notifications as a result of a received FCM
        // message, here is where that should be initiated. See sendNotification method below.

//        sendNotification(remoteMessage.getFrom(),remoteMessage.getNotification().getBody());
//        sendNotification(remoteMessage.getNotification().getBody());
    }


    private void sendNotification(String from, String body) {

        new Handler(Looper.getMainLooper()).post(new Runnable() {
            @Override
            public void run() {
                Toast.makeText(MyFirebaseMessagingService.this.getApplicationContext(), from + "\n" + body, Toast.LENGTH_SHORT).show();
            }
        });
    }


    @SuppressLint("ObsoleteSdkInt")
    private void sendNotification(String messageBody) {
        Intent intent = new Intent(this, PendingActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        PendingIntent pendingIntent = PendingIntent.getActivity(this, 0, intent,
                PendingIntent.FLAG_ONE_SHOT | PendingIntent.FLAG_IMMUTABLE);
        String puk = stock.getString("noti", "");
        String channelId = pref_ch;
        Uri defaultSoundUri = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
        NotificationCompat.Builder notificationBuilder =
                new NotificationCompat.Builder(this, channelId)
                        .setSmallIcon(R.drawable.logo)
                        .setContentTitle(puk + "My new notification")
                        .setContentText(messageBody)
                        .setPriority(NotificationManager.IMPORTANCE_MAX)
                        .setAutoCancel(true)
                        .setSound(defaultSoundUri)
                        .setFullScreenIntent(pendingIntent, true)
                        .setContentIntent(pendingIntent);

        NotificationManager notificationManager =
                (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);

        // Since android Oreo notification channel is needed.
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel = new NotificationChannel(channelId,
                    pref_ch,
                    NotificationManager.IMPORTANCE_HIGH);
            notificationManager.createNotificationChannel(channel);
        }

        notificationManager.notify(123, notificationBuilder.build());
    }
}
